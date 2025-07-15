<?php
require_once '../config/autoload_config.php';


if (!isset($_GET['id'])) {
    die("Thiếu ID tuyến.");
}

$route_id = intval($_GET['id']);

// Lấy tuyến hiện tại
$stmt = $conn->prepare("SELECT * FROM routes WHERE route_id = ?");
$stmt->bind_param("i", $route_id);
$stmt->execute();
$route = $stmt->get_result()->fetch_assoc();

if (!$route) {
    die("Không tìm thấy tuyến.");
}

// Đổi tên tuyến thành “(Ngưng hoạt động)”
$new_departure = $route['departure_location'] . " (Ngưng)";
$new_arrival = $route['arrival_location'] . " (hoạt động)";
$update = $conn->prepare("UPDATE routes SET departure_location = ?, arrival_location = ? WHERE route_id = ?");
$update->bind_param("ssi", $new_departure, $new_arrival, $route_id);

if ($update->execute()) {
    header("Location: routes.php");
    exit();
} else {
    echo "Không thể cập nhật tuyến: " . $update->error;
}
?>
