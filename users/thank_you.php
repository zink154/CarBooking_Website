<?php
// thank_you.php

require_once __DIR__ . '/../config/auth.php';

if (!isset($_GET['booking_id'])) {
    echo "Không tìm thấy đơn đặt xe.";
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn
$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number, r.departure_location, r.arrival_location
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy thông tin đơn đặt.";
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
</head>
<body>
    <h2>🎉 Đặt xe thành công!</h2>

    <p><strong>Mã đơn:</strong> #<?= $booking['booking_id'] ?></p>
    <p><strong>Xe:</strong> <?= $booking['car_brand'] ?> (<?= $booking['plate_number'] ?>)</p>
    <p><strong>Tuyến:</strong> <?= $booking['departure_location'] ?> → <?= $booking['arrival_location'] ?></p>
    <p><strong>Thời gian:</strong> <?= $booking['pickup_datetime'] ?> → <?= $booking['return_datetime'] ?></p>
    <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_price'], 0) ?> VND</p>
    <p><strong>Trạng thái:</strong> <?= ucfirst($booking['status']) ?></p>

    <br>
    <a href="my_bookings.php">📄 Xem lịch sử đặt xe</a> |
    <a href="index.php">🏠 Về trang chủ</a>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>

