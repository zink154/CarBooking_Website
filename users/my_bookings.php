<?php
// my_bookings.php

/**
 * This page displays the booking history of the logged-in user with pagination.
 * Features:
 *  - Fetch user's bookings with related car, route, and payment information.
 *  - Display bookings in pages (10 bookings per page).
 *  - Show booking details and available actions (payment, cancel, rate).
 */

require_once __DIR__ . '/../config/auth.php';

date_default_timezone_set('Asia/Ho_Chi_Minh'); 

$user_id = $_SESSION['user_id'];

// --- Pagination setup ---
$limit = 10; // Number of bookings per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// --- Get total bookings count ---
$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
$countStmt->bind_param("i", $user_id);
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// --- Fetch bookings with limit and offset ---
$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number, r.departure_location, r.arrival_location, 
           p.status AS payment_status
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    LEFT JOIN payments p ON b.booking_id = p.booking_id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Lịch sử đặt xe của bạn</h2>

    <!-- Display session success message -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow-sm">
                <thead class="table-secondary">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Xe</th>
                        <th>Tuyến</th>
                        <th>Thời gian</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $booking['booking_id'] ?></td>
                            <td><?= $booking['car_brand'] ?> (<?= $booking['plate_number'] ?>)</td>
                            <td><?= $booking['departure_location'] ?> → <?= $booking['arrival_location'] ?></td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($booking['pickup_datetime'])) ?><br>
                                → <?= date('d/m/Y H:i', strtotime($booking['return_datetime'])) ?>
                            </td>
                            <td><?= number_format($booking['total_price'], 0, ',', '.') ?> VND</td>
                            <td>
                                <?php
                                    $status = $booking['status'];
                                    $badge = 'light';
                                    $status_text = '';

                                    switch ($status) {
                                        case 'booked':
                                            $badge = 'warning';
                                            $status_text = 'Đã đặt';
                                            break;
                                        case 'confirmed':
                                            $badge = 'primary';
                                            $status_text = 'Đã xác nhận';
                                            break;
                                        case 'processing':
                                            $badge = 'info';
                                            $status_text = 'Đang xử lý';
                                            break;
                                        case 'completed':
                                            $badge = 'success';
                                            $status_text = 'Hoàn tất';
                                            break;
                                        case 'cancelled':
                                            $badge = 'secondary';
                                            $status_text = 'Đã hủy';
                                            break;
                                        default:
                                            $status_text = 'Không xác định';
                                    }
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $status_text ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($booking['status'] === 'booked'): ?>
                                    <?php if (empty($booking['payment_status']) || $booking['payment_status'] === 'unpaid'): ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="payment.php?booking_id=<?= $booking['booking_id'] ?>" class="btn btn-sm btn-success">Thanh toán</a>
                                            <form action="cancel_booking.php" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');" class="m-0">
                                                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy đơn</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <span class="text-success align-self-center">✔ Đã thanh toán</span>
                                            <form action="cancel_booking.php" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');" class="m-0">
                                                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Hủy đơn</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                <?php elseif ($booking['status'] === 'completed'): ?>
                                    <?php
                                    // Check if the booking has been rated
                                    $checkRating = $conn->prepare("SELECT * FROM ratings WHERE booking_id = ?");
                                    $checkRating->bind_param("i", $booking['booking_id']);
                                    $checkRating->execute();
                                    $rated = $checkRating->get_result()->num_rows > 0;
                                    ?>
                                    <?php if (!$rated): ?>
                                        <a href="rate_booking.php?booking_id=<?= $booking['booking_id'] ?>" class="btn btn-sm btn-outline-primary">Đánh giá</a>
                                    <?php else: ?>
                                        <span class="text-success">✔ Đã đánh giá</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=1">«</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $totalPages ?>">»</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-info text-center">Bạn chưa có đơn đặt xe nào.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="my_ratings.php" class="btn btn-warning me-2">⭐ Xem đánh giá của tôi</a>
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-secondary">Về trang chủ</a>
    </div>
</div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
