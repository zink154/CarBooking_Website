<?php
require_once '../config/autoload_config.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID xe.");
}

$car_id = $_GET['id'];

// Cập nhật trạng thái xe thành 'maintenance' (hoặc 'inactive' nếu bạn mở rộng thêm)
$stmt = $conn->prepare("UPDATE cars SET status = 'maintenance' WHERE car_id = ?");
$stmt->bind_param("i", $car_id);

if ($stmt->execute()) {
    header("Location: vehicles.php");
    exit();
} else {
    echo "Không thể ẩn xe: " . $stmt->error;
}
?>