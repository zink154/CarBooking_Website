<?php
// cancel_booking.php

require_once __DIR__ . '/../config/auth.php';

$booking_id = $_REQUEST['booking_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$booking_id || !$user_id) {
    header("Location: my_bookings.php");
    exit();
}

// Kiểm tra xem đơn đã thanh toán chưa
$check = $conn->prepare("SELECT * FROM payments WHERE booking_id = ?");
$check->bind_param("i", $booking_id);
$check->execute();
$payment_exists = $check->get_result()->num_rows > 0;

if ($payment_exists) {
    $_SESSION['success'] = "Đơn này đã thanh toán, không thể hủy.";
    header("Location: my_bookings.php");
    exit();
}

// Kiểm tra trạng thái đơn
$stmt = $conn->prepare("SELECT car_id FROM bookings WHERE booking_id = ? AND user_id = ? AND status = 'booked'");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['success'] = "Không thể hủy đơn này.";
    header("Location: my_bookings.php");
    exit();
}

$booking = $result->fetch_assoc();
$car_id = $booking['car_id'];

// Hủy đơn
$update = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
$update->bind_param("i", $booking_id);
$update->execute();

// Trả lại trạng thái xe
$conn->query("UPDATE cars SET status = 'available' WHERE car_id = $car_id");

$_SESSION['success'] = "Đơn đã bị hủy.";
header("Location: my_bookings.php");
exit();
