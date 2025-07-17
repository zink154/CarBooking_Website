<?php
// booking_process.php
require_once __DIR__ . '/../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy thông tin từ POST
    $user_id = $_SESSION['user_id'] ?? null;
    $route_id = $_POST['route_id'];
    $car_id = $_POST['car_id'];
    $pickup_datetime = $_POST['pickup_datetime'];
    $return_datetime = $_POST['return_datetime'];
    $total_price = $_POST['total_price'];

    // Kiểm tra người dùng đã đăng nhập chưa
    if (!$user_id) {
        echo "Bạn cần đăng nhập để đặt xe.";
        exit();
    }

    // Tạo đơn đặt xe
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, route_id, pickup_datetime, return_datetime, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissd", $user_id, $car_id, $route_id, $pickup_datetime, $return_datetime, $total_price);

    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id;

        // (Tuỳ chọn) cập nhật trạng thái xe thành 'in_use'
        $conn->query("UPDATE cars SET status = 'in_use' WHERE car_id = $car_id");

        // Chuyển đến trang thanh toán
        header("Location: " . BASE_URL . "/users/payment.php?booking_id=" . $booking_id);
        exit();
    } else {
        echo "Đã có lỗi xảy ra: " . $stmt->error;
    }
} else {
    header("Location: booking_form.php");
    exit();
}
