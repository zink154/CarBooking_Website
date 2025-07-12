<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Kiểm tra đơn hợp lệ và trạng thái là 'booked'
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

    // Hủy đơn (cập nhật status)
    $update = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
    $update->bind_param("i", $booking_id);
    $update->execute();

    // Trả lại trạng thái xe
    $conn->query("UPDATE cars SET status = 'available' WHERE car_id = $car_id");

    $_SESSION['success'] = "Đơn đã được hủy thành công.";
    header("Location: my_bookings.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
