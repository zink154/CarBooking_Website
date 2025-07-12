<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

if (!isset($_GET['booking_id'])) {
    echo "Thiếu mã đơn đặt xe.";
    exit();
}

$booking_id = $_GET['booking_id'];

// Truy vấn thông tin đơn đặt xe
$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number, r.departure_location, r.arrival_location 
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy đơn đặt xe.";
    exit();
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
</head>
<body>
    <h2>Thanh toán đơn đặt xe</h2>

    <p><strong>Xe:</strong> <?= $booking['car_brand'] ?> (<?= $booking['plate_number'] ?>)</p>
    <p><strong>Tuyến:</strong> <?= $booking['departure_location'] ?> → <?= $booking['arrival_location'] ?></p>
    <p><strong>Thời gian:</strong> <?= $booking['pickup_datetime'] ?> → <?= $booking['return_datetime'] ?></p>
    <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_price'], 0) ?> VND</p>

    <form action="payment_process.php" method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">

        <label>Chọn phương thức thanh toán:</label><br>
        <select name="method" required>
            <option value="credit_card">Thẻ tín dụng</option>
            <option value="e_wallet">Ví điện tử</option>
            <option value="bank_transfer">Chuyển khoản</option>
        </select><br><br>

        <button type="submit">Xác nhận thanh toán</button>
    </form>
</body>
</html>
