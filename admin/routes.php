<?php
require_once __DIR__ . '/../config/autoload_config.php';

$result = $conn->query("SELECT * FROM routes");
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tuyến đường</title>
</head>
<body>
    <h2>Danh sách tuyến đường</h2>
    <a href="add_route.php">➕ Thêm tuyến mới</a><br><br>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Điểm đi</th>
            <th>Điểm đến</th>
            <th>Khoảng cách (km)</th>
            <th>Hành động</th>
        </tr>
        <?php while ($route = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $route['route_id'] ?></td>
                <td><?= $route['departure_location'] ?></td>
                <td><?= $route['arrival_location'] ?></td>
                <td><?= $route['distance_km'] ?></td>
                <td>
                    <a href="edit_route.php?id=<?= $route['route_id'] ?>">Sửa</a> |
                    <a href="delete_route.php?id=<?= $route['route_id'] ?>" onclick="return confirm('Xác nhận xóa tuyến này?')">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br><a href="vehicles.php">← Quản lý xe</a>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
