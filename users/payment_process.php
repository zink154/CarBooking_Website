<?php
// payment_process.php

require_once __DIR__ . '/../config/auth.php';

// Kiểm tra POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST['booking_id'];
    $method = $_POST['method'];
    $user_id = $_SESSION['user_id'];

    $allowed_methods = ['vietqr', 'cash'];
    if (!in_array($method, $allowed_methods)) {
        echo "Phương thức thanh toán không hợp lệ.";
        exit();
    }

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

    // Trạng thái thanh toán
    $status = ($method === 'vietqr') ? 'paid' : 'unpaid';

    // Thêm vào bảng payments
    $insert = $conn->prepare("INSERT INTO payments (booking_id, method, status) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $booking_id, $method, $status);

    if ($insert->execute()) {
        echo "Đã ghi nhận thanh toán bằng phương thức: " . htmlspecialchars($method);
        // Redirect nếu cần
        // header("Location: payment_success.php");
    } else {
        echo "Có lỗi xảy ra khi xử lý thanh toán.";
    }
}
?>
