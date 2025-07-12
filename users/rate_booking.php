<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

if (!isset($_GET['booking_id'])) {
    echo "Thiếu mã đơn.";
    exit();
}

$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn đặt xe đã hoàn tất
$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number 
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    WHERE b.booking_id = ? AND b.user_id = ? AND b.status = 'completed'
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy đơn hoàn tất để đánh giá.";
    exit();
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đánh giá chuyến đi</title>
</head>
<body>
    <h2>Đánh giá xe: <?= $booking['car_brand'] ?> (<?= $booking['plate_number'] ?>)</h2>

    <form action="rate_booking_process.php" method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
        <input type="hidden" name="car_id" value="<?= $booking['car_id'] ?>">

        <label>Chọn số sao:</label><br>
        <select name="score" required>
            <option value="5">⭐⭐⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="2">⭐⭐</option>
            <option value="1">⭐</option>
        </select><br><br>

        <label>Nhận xét:</label><br>
        <textarea name="comment" rows="4" cols="50" required></textarea><br><br>

        <button type="submit">Gửi đánh giá</button>
    </form>
</body>
</html>
