<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID tuyến.");
}

$route_id = intval($_GET['id']);

$stmt = $conn->prepare("UPDATE routes SET status = 'available' WHERE route_id = ?");
$stmt->bind_param("i", $route_id);

if ($stmt->execute()) {
    header("Location: routes.php");
    exit();
} else {
    echo "Không thể khôi phục tuyến: " . $stmt->error;
}
