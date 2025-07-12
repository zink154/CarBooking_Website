<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

$user_id = $_SESSION['user_id'];

// Lấy danh sách booking của người dùng
$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number, r.departure_location, r.arrival_location
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt xe</title>
</head>
<body>
    <h2>Lịch sử đặt xe của bạn</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color:green"><?= $_SESSION['success'] ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Mã đơn</th>
                <th>Xe</th>
                <th>Tuyến</th>
                <th>Thời gian</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            <?php while ($booking = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $booking['booking_id'] ?></td>
                    <td><?= $booking['car_brand'] ?> (<?= $booking['plate_number'] ?>)</td>
                    <td><?= $booking['departure_location'] ?> → <?= $booking['arrival_location'] ?></td>
                    <td>
                        <?= $booking['pickup_datetime'] ?><br>→ <?= $booking['return_datetime'] ?>
                    </td>
                    <td><?= number_format($booking['total_price'], 0) ?> VND</td>
                    <td><?= ucfirst($booking['status']) ?></td>
                    <td>
                        <?php if ($booking['status'] === 'booked'): ?>
                            <form action="cancel_booking.php" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                <button type="submit">Hủy đơn</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Bạn chưa có đơn đặt xe nào.</p>
    <?php endif; ?>

    <?php if ($booking['status'] === 'completed'): ?>
        <?php
        // Kiểm tra đã đánh giá chưa
        $checkRating = $conn->prepare("SELECT * FROM ratings WHERE booking_id = ?");
        $checkRating->bind_param("i", $booking['booking_id']);
        $checkRating->execute();
        $rated = $checkRating->get_result()->num_rows > 0;
        ?>
        <?php if (!$rated): ?>
            <a href="rate_booking.php?booking_id=<?= $booking['booking_id'] ?>">Đánh giá</a>
        <?php else: ?>
            Đã đánh giá
        <?php endif; ?>
    <?php endif; ?>

    <br><a href="index.php">🏠 Về trang chủ</a>
</body>
</html>
