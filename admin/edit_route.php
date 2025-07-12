<?php
require_once '../config/autoload_config.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID tuyến đường.");
}

$route_id = intval($_GET['id']);

// Xử lý cập nhật khi gửi form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $departure = trim($_POST['departure_location']);
    $arrival = trim($_POST['arrival_location']);
    $distance = floatval($_POST['distance_km']);

    if ($departure === $arrival) {
        echo "Điểm đi và điểm đến không được trùng nhau.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE routes SET departure_location=?, arrival_location=?, distance_km=? WHERE route_id=?");
    $stmt->bind_param("ssdi", $departure, $arrival, $distance, $route_id);

    if ($stmt->execute()) {
        header("Location: routes.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật: " . $stmt->error;
    }
}

// Lấy thông tin tuyến hiện tại
$stmt = $conn->prepare("SELECT * FROM routes WHERE route_id = ?");
$stmt->bind_param("i", $route_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy tuyến.");
}

$route = $result->fetch_assoc();

// Loại bỏ cụm "(Ngưng" và "(hoạt động)" nếu có
$departure_clean = preg_replace('/\(.*?\)/', '', $route['departure_location']);
$arrival_clean = preg_replace('/\(.*?\)/', '', $route['arrival_location']);
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa tuyến đường</title>
</head>
<body>
    <h2>Sửa tuyến: <?= htmlspecialchars($departure_clean) ?> → <?= htmlspecialchars($arrival_clean) ?></h2>

    <form action="" method="POST">
        <label>Điểm đi:</label><br>
        <input type="text" name="departure_location" value="<?= htmlspecialchars(trim($departure_clean)) ?>" required><br><br>

        <label>Điểm đến:</label><br>
        <input type="text" name="arrival_location" value="<?= htmlspecialchars(trim($arrival_clean)) ?>" required><br><br>

        <label>Khoảng cách (km):</label><br>
        <input type="number" step="0.1" name="distance_km" value="<?= $route['distance_km'] ?>" required><br><br>

        <button type="submit">Cập nhật</button>
    </form>

    <br><a href="routes.php">← Quay lại danh sách tuyến</a>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>