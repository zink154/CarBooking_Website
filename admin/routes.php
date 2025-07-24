<?php 
/**
 * routes.php
 *
 * This page displays the list of routes in the system for admin management.
 * Features:
 *  - List all routes (with departure, arrival, distance, and status).
 *  - Show route status (available or unavailable) using a badge.
 *  - Provide actions to edit a route or mark it as unavailable (via delete_route.php).
 *  - Allow admin to add new routes.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session handling
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication

// --- Fetch all routes ---
$result = $conn->query("SELECT * FROM routes");

// Back URL (previous page or vehicles.php as default)
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
    <!-- Page title and action buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Danh sách tuyến</h2>
        <div class="d-flex gap-2">
            <a href="<?= htmlspecialchars($back_url) ?>" class="btn btn-secondary">← Quay lại</a>
            <a href="add_route.php" class="btn btn-yellow">➕ Thêm tuyến mới</a>
            <a href="bookings.php" class="btn btn-secondary">Xem danh sách đơn →</a>
        </div>
    </div>

    <!-- Routes Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-primary">
          <tr class="text-center">
            <th>ID</th>
            <th>Điểm đi</th>
            <th>Điểm đến</th>
            <th>Khoảng cách (km)</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($route = $result->fetch_assoc()): ?>
              <tr>
                <td class="text-center"><?= htmlspecialchars($route['route_id']) ?></td>
                <td><?= htmlspecialchars($route['departure_location']) ?></td>
                <td><?= htmlspecialchars($route['arrival_location']) ?></td>
                <td class="text-center">
                  <?= htmlspecialchars(rtrim(rtrim($route['distance_km'], '0'), '.')) ?>
                </td>
                <td class="text-center">
                  <?php
                  // Display status with colored badges
                  echo $route['status'] === 'available'
                      ? '<span class="badge bg-success">Hoạt động</span>'
                      : '<span class="badge bg-secondary">Ngưng hoạt động</span>';
                  ?>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="edit_route.php?id=<?= $route['route_id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <?php if ($route['status'] === 'available'): ?>
                        <a href="delete_route.php?id=<?= $route['route_id'] ?>" 
                          class="btn btn-danger btn-sm"
                          onclick="return confirm('Xác nhận ngưng tuyến này?')">Ngưng</a>
                    <?php else: ?>
                        <a href="restore_route.php?id=<?= $route['route_id'] ?>" 
                          class="btn btn-success btn-sm"
                          onclick="return confirm('Khôi phục tuyến này?')">Khôi phục</a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted">Không có tuyến đường nào.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
