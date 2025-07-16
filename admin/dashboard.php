
<?php include __DIR__ . '/../views/admin_header.php'; ?>

<?php
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

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            max-width: 960px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .stat-icon {
            font-size: 1.8rem;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2 class="mb-4 text-center fw-bold">📊 Tổng quan hệ thống</h2>

    <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">🧾</span> Tổng đơn đặt xe</h5>
                    <p class="card-text fs-4 fw-bold"><?= $totalBookings ?></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-info h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">✅</span> Đơn hoàn tất</h5>
                    <p class="card-text fs-4 fw-bold"><?= $completedBookings ?></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">🚗</span> Xe sẵn sàng</h5>
                    <p class="card-text fs-4 fw-bold"><?= $availableCars ?></p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">💰</span> Doanh thu</h5>
                    <p class="card-text fs-4 fw-bold"><?= number_format($totalRevenue, 0) ?> VND</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-danger h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">🌟</span> Đánh giá trung bình</h5>
                    <p class="card-text fs-4 fw-bold"><?= $avgRating ?> ⭐</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="vehicles.php" class="btn btn-outline-secondary m-2">🚘 Quản lý xe</a>
        <a href="routes.php" class="btn btn-outline-secondary m-2">🛣️ Quản lý tuyến</a>
        <a href="bookings.php" class="btn btn-outline-secondary m-2">📑 Quản lý đơn</a>
    </div>
</div>

</body>
</html>

<?php include __DIR__ . '/../views/admin_footer.php'; ?>
