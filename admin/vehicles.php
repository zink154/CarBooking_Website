<?php
require_once __DIR__ . '/../config/autoload_config.php';

// (Tùy chọn) Kiểm tra role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    die("Bạn không có quyền truy cập.");
}

$result = $conn->query("SELECT * FROM cars");
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý xe</title>
</head>
<body>
    <h2>Danh sách xe</h2>
    <a href="add_vehicle.php">➕ Thêm xe mới</a><br><br>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Loại</th>
            <th>Hiệu</th>
            <th>Biển số</th>
            <th>Giá/km</th>
            <th>Số chỗ</th>
            <th>Trạng thái</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>
        <?php while ($car = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $car['car_id'] ?></td>
                <td><?= $car['car_type'] ?></td>
                <td><?= $car['car_brand'] ?></td>
                <td><?= $car['plate_number'] ?></td>
                <td><?= number_format($car['price_per_km'], 0) ?> VND/km</td>
                <td><?= $car['capacity'] ?></td>
                <td>
                    <?php
                        if ($car['status'] === 'maintenance') {
                            echo "<span style='color:red'>Ngưng hoạt động</span>";
                        } elseif ($car['status'] === 'available') {
                            echo "<span style='color:green'>Sẵn sàng</span>";
                        } elseif ($car['status'] === 'in_use') {
                            echo "<span style='color:orange'>Đang sử dụng</span>";
                        } else {
                            echo ucfirst($car['status']);
                        }
                    ?>
                </td>
                <td><img src="<?= $car['image_url'] ?>" width="100"></td>
                <td>
                    <a href="edit_vehicle.php?id=<?= $car['car_id'] ?>">Sửa</a> |
                    <a href="delete_vehicle.php?id=<?= $car['car_id'] ?>" onclick="return confirm('Xác nhận xóa xe này?')">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
