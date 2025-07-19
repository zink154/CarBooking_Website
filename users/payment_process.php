<?php
// payment_process.php

require_once __DIR__ . '/../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = (int)$_POST['booking_id'];
    $method = $_POST['method'];
    $user_id = $_SESSION['user_id'];

    $allowed_methods = ['vietqr', 'cash'];
    if (!in_array($method, $allowed_methods)) {
        die("Phương thức thanh toán không hợp lệ.");
    }

    // Kiểm tra booking hợp lệ
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    if (!$booking) {
        die("Đơn đặt xe không hợp lệ.");
    }

    // Kiểm tra thanh toán tồn tại
    $check = $conn->prepare("SELECT * FROM payments WHERE booking_id = ?");
    $check->bind_param("i", $booking_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        die("Đơn này đã được thanh toán.");
    }

    // Xác định trạng thái thanh toán
    $status = ($method === 'vietqr') ? 'paid' : 'unpaid';

    // Thêm bản ghi vào payments
    $insert = $conn->prepare("INSERT INTO payments (booking_id, method, status) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $booking_id, $method, $status);

    if ($insert->execute()) {
        // Cập nhật trạng thái booking
        if ($method === 'vietqr') {
            $update = $conn->prepare("UPDATE bookings SET status = 'processing' WHERE booking_id = ?");
            $update->bind_param("i", $booking_id);
            $update->execute();
        }
        // Redirect sang trang thanh toán thành công
        header("Location: payment_success.php?booking_id=" . $booking_id);
        exit();
    } else {
        die("Có lỗi xảy ra khi xử lý thanh toán.");
    }
}
?>
