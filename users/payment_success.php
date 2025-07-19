<?php
// payment_success.php

/**
 * This page displays a payment success summary after the user completes a booking payment.
 * Features:
 *  - Fetch and display booking details (car, route, total price).
 *  - Show payment method and status (paid/unpaid).
 *  - Provide navigation links to view booking history or return to the homepage.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure the user is logged in

// --- Validate booking_id parameter ---
if (!isset($_GET['booking_id'])) {
    die("Thiếu mã đơn đặt xe."); // "Missing booking ID."
}

$booking_id = (int) $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// --- Fetch booking and payment details ---
$stmt = $conn->prepare("
    SELECT b.*, p.method, p.status AS payment_status, c.car_brand, c.plate_number, 
           r.departure_location, r.arrival_location
    FROM bookings b
    LEFT JOIN payments p ON b.booking_id = p.booking_id
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// --- Check if booking exists ---
if ($result->num_rows === 0) {
    die("Không tìm thấy đơn đặt xe."); // "Booking not found."
}

$booking = $result->fetch_assoc();
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h3 class="text-success mb-4">Thanh toán thành công!</h3>

            <!-- Display booking details -->
            <ul class="list-group text-start mb-4">
                <li class="list-group-item"><strong>Mã đơn:</strong> <?= $booking['booking_id'] ?></li>
                <li class="list-group-item"><strong>Xe:</strong> <?= htmlspecialchars($booking['car_brand']) ?> (<?= htmlspecialchars($booking['plate_number']) ?>)</li>
                <li class="list-group-item"><strong>Tuyến:</strong> <?= htmlspecialchars($booking['departure_location']) ?> → <?= htmlspecialchars($booking['arrival_location']) ?></li>
                <li class="list-group-item"><strong>Tổng tiền:</strong> <?= number_format($booking['total_price'], 0, ',', '.') ?> VNĐ</li>
                <li class="list-group-item"><strong>Phương thức thanh toán:</strong> <?= strtoupper($booking['method']) ?></li>
                <li class="list-group-item"><strong>Trạng thái thanh toán:</strong> 
                    <?= ($booking['payment_status'] === 'paid') 
                        ? '<span class="text-success">Đã thanh toán</span>' 
                        : '<span class="text-warning">Chưa thanh toán</span>' ?>
                </li>
            </ul>

            <!-- Navigation buttons -->
            <a href="my_bookings.php" class="btn btn-primary">Xem lịch sử đặt xe</a>
            <a href="/" class="btn btn-outline-secondary">Về trang chủ</a>
        </div>
    </div>
</div>
</body>
</html>
<?php include __DIR__ . '/../views/footer.php'; ?>
