<?php 
/**
 * dashboard.php
 *
 * This script displays the admin dashboard with an overview of the system.
 * It includes:
 *  - Total bookings and completed bookings.
 *  - Total number of cars currently available.
 *  - Total revenue from completed bookings.
 *  - Average rating and total number of ratings.
 *  - Quick navigation links to manage vehicles, routes, bookings, and ratings.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session handling
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication check

// --- Booking statistics ---
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total']; // Total bookings
$completedBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'completed'")->fetch_assoc()['total']; // Completed bookings

// --- Car statistics ---
$availableCars = $conn->query("SELECT COUNT(*) AS total FROM cars WHERE status = 'available'")->fetch_assoc()['total']; // Cars available

// --- Revenue statistics ---
$totalRevenue = $conn->query("SELECT SUM(total_price) AS total FROM bookings WHERE status = 'completed'")->fetch_assoc()['total'] ?? 0;

// --- Rating statistics (average score & total ratings) ---
$ratingData = $conn->query("SELECT AVG(score) AS avg, COUNT(*) AS total FROM ratings")->fetch_assoc();
$avgRating = $ratingData['avg'] ? number_format($ratingData['avg'], 1) : "Chưa có"; // Average rating
$totalRatings = $ratingData['total'] ?? 0; // Total number of ratings
?>

<?php include __DIR__ . '/../views/header.php'; ?>

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

    <!-- Dashboard Statistics Cards -->
    <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <!-- Total Bookings -->
        <div class="col">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">🧾</span> Tổng đơn đặt xe</h5>
                    <p class="card-text fs-4 fw-bold"><?= $totalBookings ?></p>
                </div>
            </div>
        </div>
        <!-- Completed Bookings -->
        <div class="col">
            <div class="card border-info h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">✅</span> Đơn hoàn tất</h5>
                    <p class="card-text fs-4 fw-bold"><?= $completedBookings ?></p>
                </div>
            </div>
        </div>
        <!-- Available Cars -->
        <div class="col">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">🚗</span> Xe sẵn sàng</h5>
                    <p class="card-text fs-4 fw-bold"><?= $availableCars ?></p>
                </div>
            </div>
        </div>
        <!-- Total Revenue -->
        <div class="col">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">💰</span> Doanh thu</h5>
                    <p class="card-text fs-4 fw-bold"><?= number_format($totalRevenue, 0) ?> VND</p>
                </div>
            </div>
        </div>
        <!-- Average Ratings -->
        <div class="col">
            <div class="card border-danger h-100">
                <div class="card-body">
                    <h5 class="card-title"><span class="stat-icon">🌟</span> Đánh giá trung bình</h5>
                    <?php if ($avgRating === "Chưa có"): ?>
                        <p class="card-text fs-4 fw-bold"><?= $avgRating ?></p>
                    <?php else: ?>
                        <p class="card-text fs-4 fw-bold">
                            <?= $avgRating ?> ⭐ (<?= $totalRatings ?> đánh giá)
                        </p>
                        <!-- Link to all ratings page -->
                        <a href="all_ratings.php" class="btn btn-sm btn-outline-danger mt-2">📋 Xem tất cả đánh giá</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick navigation buttons -->
    <div class="text-center">
        <a href="vehicles.php" class="btn btn-outline-secondary m-2">🚘 Quản lý xe</a>
        <a href="routes.php" class="btn btn-outline-secondary m-2">🛣️ Quản lý tuyến</a>
        <a href="bookings.php" class="btn btn-outline-secondary m-2">📑 Quản lý đơn</a>
        <a href="all_ratings.php" class="btn btn-outline-secondary m-2">🌟 Quản lý đánh giá</a>
        <a href="users_management.php" class="btn btn-outline-secondary m-2">👤 Quản lý người dùng</a>
    </div>
</div>

</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
