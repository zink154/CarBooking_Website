<?php
require_once __DIR__ . '/../config/autoload_config.php';
$result = $conn->query("SELECT * FROM routes");
$back_url = $_SERVER['HTTP_REFERER'] ?? 'vehicles.php';
?>



<?php include __DIR__ . '/../views/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý tuyến đường</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary mb-0">Danh sách tuyến đường</h2>
      <a href="add_route.php" class="btn btn-yellow">➕ Thêm tuyến mới</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Điểm đi</th>
            <th>Điểm đến</th>
            <th>Khoảng cách (km)</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($route = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($route['route_id']) ?></td>
              <td><?= htmlspecialchars($route['departure_location']) ?></td>
              <td><?= htmlspecialchars($route['arrival_location']) ?></td>
              <td><?= htmlspecialchars(rtrim(rtrim($route['distance_km'], '0'), '.')) ?></td>
              <td>
                <a href="edit_route.php?id=<?= $route['route_id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                <a href="delete_route.php?id=<?= $route['route_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa tuyến này?')">Xóa</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4 d-flex justify-content-between">
      <a href="<?= htmlspecialchars($back_url) ?>" class="btn btn-outline-secondary">← Quay lại</a>
      <a href="bookings.php" class="btn btn-info">📋 Xem đơn đặt xe</a>
    </div>
  </div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
