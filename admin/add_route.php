<?php
require_once '../config/autoload_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $departure = trim($_POST['departure_location']);
    $arrival = trim($_POST['arrival_location']);
    $distance = floatval($_POST['distance_km']);

    if ($departure === $arrival) {
        echo "Điểm đi và điểm đến không được trùng nhau.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO routes (departure_location, arrival_location, distance_km) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $departure, $arrival, $distance);

    if ($stmt->execute()) {
        header("Location: routes.php");
        exit();
    } else {
        echo "Lỗi khi thêm tuyến: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tuyến đường</title>
</head>
<body>
    <h2>Thêm tuyến đường mới</h2>

    <form action="" method="POST">
        <label>Điểm đi:</label><br>
        <input type="text" name="departure_location" required><br><br>

        <label>Điểm đến:</label><br>
        <input type="text" name="arrival_location" required><br><br>

        <label>Khoảng cách (km):</label><br>
        <input type="number" name="distance_km" step="0.1" required><br><br>

        <button type="submit">Thêm tuyến</button>
    </form>

    <br><a href="routes.php">← Quay lại danh sách tuyến</a>
</body>
</html>
