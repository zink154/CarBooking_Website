<?php
require_once __DIR__ . '/../config/autoload_config.php';

// Kiểm tra quyền admin
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .car-img {
            width: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">🚘 Danh sách xe</h2>
        <a href="add_vehicle.php" class="btn btn-success">➕ Thêm xe mới</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle shadow-sm bg-white">
            <thead class="table-dark">
                <tr class="text-center">
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
            </thead>
            <tbody>
                <?php while ($car = $result->fetch_assoc()): ?>
                    <tr class="text-center">
                        <td><?= $car['car_id'] ?></td>
                        <td><?= htmlspecialchars($car['car_type']) ?></td>
                        <td><?= htmlspecialchars($car['car_brand']) ?></td>
                        <td><?= htmlspecialchars($car['plate_number']) ?></td>
                        <td><?= number_format($car['price_per_km'], 0) ?> VND/km</td>
                        <td><?= $car['capacity'] ?></td>
                        <td>
                            <?php
                                switch ($car['status']) {
                                    case 'maintenance':
                                        echo '<span class="badge bg-danger">Ngưng hoạt động</span>';
                                        break;
                                    case 'available':
                                        echo '<span class="badge bg-success">Sẵn sàng</span>';
                                        break;
                                    case 'in_use':
                                        echo '<span class="badge bg-warning text-dark">Đang sử dụng</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-secondary">' . ucfirst($car['status']) . '</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php if ($car['image_url']): ?>
                                <img src="<?= htmlspecialchars($car['image_url']) ?>" class="car-img" alt="Ảnh xe">
                            <?php else: ?>
                                <span class="text-muted">Không có ảnh</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_vehicle.php?id=<?= $car['car_id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                            <a href="delete_vehicle.php?id=<?= $car['car_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa xe này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
