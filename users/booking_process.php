<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

// Kiểm tra phương thức POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];
    $route_id = $_POST['route_id'];
    $pickup_datetime = $_POST['pickup_datetime'];
    $return_datetime = $_POST['return_datetime'];
    $total_price = $_POST['total_price'];

    // Kiểm tra xe còn sẵn sàng hay không
    $checkCar = $conn->prepare("SELECT status FROM cars WHERE car_id = ?");
    $checkCar->bind_param("i", $car_id);
    $checkCar->execute();
    $result = $checkCar->get_result();

    if ($result->num_rows === 0) {
        die("Xe không tồn tại.");
    }

    $car = $result->fetch_assoc();
    if ($car['status'] !== 'available') {
        die("Xe đã được đặt hoặc không khả dụng.");
    }

    // Tạo đơn đặt xe
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, route_id, pickup_datetime, return_datetime, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissd", $user_id, $car_id, $route_id, $pickup_datetime, $return_datetime, $total_price);

    if ($stmt->execute()) {
        // Cập nhật trạng thái xe thành 'in_use'
        $conn->query("UPDATE cars SET status = 'in_use' WHERE car_id = $car_id");

        $booking_id = $stmt->insert_id;

        // Chuyển đến trang thanh toán
        header("Location: payment.php?booking_id=$booking_id");
        exit();
    } else {
        echo "Lỗi khi đặt xe: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: booking_form.php");
    exit();
}
?>
