<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID xe.");
}

$car_id = $_GET['id'];

// Cập nhật trạng thái xe thành 'unavailable'
$stmt = $conn->prepare("UPDATE cars SET status = 'unavailable' WHERE car_id = ?");
$stmt->bind_param("i", $car_id);

if ($stmt->execute()) {
    header("Location: vehicles.php");
    exit();
} else {
    echo "Không thể cập nhật trạng thái xe: " . $stmt->error;
}
?>
