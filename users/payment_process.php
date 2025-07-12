<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

// Kiểm tra POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST['booking_id'];
    $method = $_POST['method'];
    $user_id = $_SESSION['user_id'];

    // Kiểm tra booking có tồn tại và đúng user
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        echo "Đơn đặt xe không hợp lệ.";
        exit();
    }

    // Kiểm tra đã có thanh toán chưa
    $check = $conn->prepare("SELECT * FROM payments WHERE booking_id = ?");
    $check->bind_param("i", $booking_id);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;

    if ($exists) {
        echo "Đơn này đã được thanh toán.";
        exit();
    }

    // Thêm vào bảng payments
    $insert = $conn->prepare("INSERT INTO payments (booking_id, method, status) VALUES (?, ?, 'paid')");
    $insert->bind_param("is", $booking_id, $method);

    if ($insert->execute()) {
        // Cập nhật trạng thái đơn hàng
        $conn->query("UPDATE bookings SET status = 'processing' WHERE booking_id = $booking_id");

        // Gửi thông báo (nếu có) – có thể tích hợp email sau

        // Chuyển hướng đến lịch sử hoặc trang cảm ơn
        header("Location: thank_you.php?booking_id=$booking_id");
        exit();
    } else {
        echo "Lỗi thanh toán: " . $insert->error;
    }
} else {
    header("Location: index.php");
    exit();
}
?>
