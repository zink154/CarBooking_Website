<?php
require_once __DIR__ . '/../config/autoload_config.php';


// Thống kê đơn hàng
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$completedBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'completed'")->fetch_assoc()['total'];

// Thống kê xe
$availableCars = $conn->query("SELECT COUNT(*) AS total FROM cars WHERE status = 'available'")->fetch_assoc()['total'];

// Doanh thu
$totalRevenue = $conn->query("SELECT SUM(total_price) AS total FROM bookings WHERE status = 'completed'")->fetch_assoc()['total'] ?? 0;

// Đánh giá trung bình
$avgRating = $conn->query("SELECT AVG(score) AS avg FROM ratings")->fetch_assoc()['avg'];
$avgRating = $avgRating ? round($avgRating, 1) : "Chưa có";
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard quản trị</title>
    </head>
        <body>
            <h2>📊 Tổng quan hệ thống</h2>

            <ul>
                <li>🧾 Tổng đơn đặt xe: <strong><?= $totalBookings ?></strong></li>
                <li>✅ Số đơn hoàn tất: <strong><?= $completedBookings ?></strong></li>
                <li>🚗 Xe đang sẵn sàng: <strong><?= $availableCars ?></strong></li>
                <li>💰 Tổng doanh thu: <strong><?= number_format($totalRevenue, 0) ?> VND</strong></li>
                <li>🌟 Đánh giá trung bình: <strong><?= $avgRating ?> ⭐</strong></li>
            </ul>

            <hr>
            <a href="vehicles.php">🚘 Quản lý xe</a> | 
            <a href="routes.php">🛣️ Quản lý tuyến</a> | 
            <a href="bookings.php">📑 Quản lý đơn</a>

        </body>
</html>
<?php include __DIR__ . '/../views/footer.php'; ?>
