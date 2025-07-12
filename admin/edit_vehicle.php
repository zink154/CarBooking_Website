<?php
require_once '../config/autoload_config.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID xe.");
}

$car_id = $_GET['id'];

// Xử lý cập nhật khi gửi form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_type = $_POST['car_type'];
    $car_brand = $_POST['car_brand'];
    $plate_number = $_POST['plate_number'];
    $price_per_km = $_POST['price_per_km'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("UPDATE cars SET car_type=?, car_brand=?, plate_number=?, price_per_km=?, capacity=?, status=?, image_url=? WHERE car_id=?");
    $stmt->bind_param("sssdissi", $car_type, $car_brand, $plate_number, $price_per_km, $capacity, $status, $image_url, $car_id);

    if ($stmt->execute()) {
        header("Location: vehicles.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật: " . $stmt->error;
    }
}

// Lấy thông tin xe hiện tại
$stmt = $conn->prepare("SELECT * FROM cars WHERE car_id = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy xe.");
}

$car = $result->fetch_assoc();
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin xe</title>
</head>
<body>
    <h2>Sửa thông tin xe: <?= $car['car_brand'] ?> (<?= $car['plate_number'] ?>)</h2>

    <form action="" method="POST">
        <label>Loại xe:</label><br>
        <input type="text" name="car_type" value="<?= $car['car_type'] ?>" required><br><br>

        <label>Hiệu xe:</label><br>
        <input type="text" name="car_brand" value="<?= $car['car_brand'] ?>" required><br><br>

        <label>Biển số:</label><br>
        <input type="text" name="plate_number" value="<?= $car['plate_number'] ?>" required><br><br>

        <label>Giá thuê / km (VND):</label><br>
        <input type="number" name="price_per_km" step="0.01" value="<?= $car['price_per_km'] ?>" required><br><br>

        <label>Số chỗ ngồi:</label><br>
        <input type="number" name="capacity" value="<?= $car['capacity'] ?>" required><br><br>

        <label>Trạng thái:</label><br>
        <select name="status">
            <option value="available" <?= $car['status'] === 'available' ? 'selected' : '' ?>>Sẵn sàng</option>
            <option value="in_use" <?= $car['status'] === 'in_use' ? 'selected' : '' ?>>Đang sử dụng</option>
            <option value="maintenance" <?= $car['status'] === 'maintenance' ? 'selected' : '' ?>>Bảo trì</option>
        </select><br><br>

        <label>URL ảnh:</label><br>
        <input type="text" name="image_url" value="<?= $car['image_url'] ?>" required><br><br>

        <button type="submit">Cập nhật</button>
    </form>

    <br><a href="vehicles.php">← Quay lại danh sách xe</a>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
