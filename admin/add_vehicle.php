<?php
require_once '../config/autoload_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_type = $_POST['car_type'];
    $car_brand = $_POST['car_brand'];
    $plate_number = $_POST['plate_number'];
    $price_per_km = $_POST['price_per_km'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO cars (car_type, car_brand, plate_number, price_per_km, capacity, status, image_url)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdis", $car_type, $car_brand, $plate_number, $price_per_km, $capacity, $status, $image_url);

    if ($stmt->execute()) {
        header("Location: vehicles.php");
        exit();
    } else {
        echo "Lỗi khi thêm xe: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm xe mới</title>
</head>
<body>
    <h2>Thêm xe mới</h2>

    <form action="" method="POST">
        <label>Loại xe:</label><br>
        <input type="text" name="car_type" required><br><br>

        <label>Hiệu xe:</label><br>
        <input type="text" name="car_brand" required><br><br>

        <label>Biển số:</label><br>
        <input type="text" name="plate_number" required><br><br>

        <label>Giá thuê / km (VND):</label><br>
        <input type="number" name="price_per_km" step="0.01" required><br><br>

        <label>Số chỗ ngồi:</label><br>
        <input type="number" name="capacity" required><br><br>

        <label>Trạng thái:</label><br>
        <select name="status">
            <option value="available">Sẵn sàng</option>
            <option value="in_use">Đang sử dụng</option>
            <option value="maintenance">Bảo trì</option>
        </select><br><br>

        <label>URL ảnh:</label><br>
        <input type="text" name="image_url" required><br><br>

        <button type="submit">Thêm xe</button>
    </form>

    <br><a href="vehicles.php">← Quay lại danh sách xe</a>
</body>
</html>
