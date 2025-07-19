<?php
// thank_you.php

/**
 * This page is displayed after a successful booking.
 * Features:
 *  - Fetch and display booking details (car, route, time, total price, and status).
 *  - Provide navigation links to booking history or home page.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure user is logged in

// --- Validate booking_id parameter ---
if (!isset($_GET['booking_id'])) {
    echo "Không tìm thấy đơn đặt xe."; // "Booking not found."
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// --- Fetch booking details ---
$stmt = $conn->prepare("
    SELECT b.*, c.car_name, c.plate_number, r.departure_location, r.arrival_location
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// --- If booking not found, show error ---
if ($result->num_rows === 0) {
    echo "Không tìm thấy thông tin đơn đặt."; // "Booking details not found."
    exit();
}

$booking = $result->fetch_assoc();
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt xe thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body text-center">
                <h2 class="text-success mb-4">🎉 Đặt xe thành công!</h2>

                <!-- Booking details -->
                <ul class="list-group text-start mb-4">
                    <li class="list-group-item"><strong>Mã đơn:</strong> #<?= $booking['booking_id'] ?></li>
                    <li class="list-group-item"><strong>Xe:</strong> <?= $booking['car_name'] ?> (<?= $booking['plate_number'] ?>)</li>
                    <li class="list-group-item"><strong>Tuyến:</strong> <?= $booking['departure_location'] ?> → <?= $booking['arrival_location'] ?></li>
                    <li class="list-group-item"><strong>Thời gian:</strong> <?= $booking['pickup_datetime'] ?> → <?= $booking['return_datetime'] ?></li>
                    <li class="list-group-item"><strong>Tổng tiền:</strong> <?= number_format($booking['total_price'], 0) ?> VND</li>
                    <li class="list-group-item"><strong>Trạng thái:</strong> <?= ucfirst($booking['status']) ?></li>
                </ul>

                <!-- Navigation buttons -->
                <a href="my_bookings.php" class="btn btn-primary me-2">📄 Xem lịch sử đặt xe</a>
                <a href="index.php" class="btn btn-outline-secondary">🏠 Về trang chủ</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
