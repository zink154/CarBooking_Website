<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';
?>

<?php include __DIR__ . '/views/header.php'; ?>

<div class="container mt-5 text-center">
    <h1 class="fw-bold">Chào mừng đến với TamHang Tourist</h1>
    <p>Hệ thống giúp bạn đặt xe nhanh chóng, tiện lợi, và an toàn.</p>

    <!-- Logo chèn ngay dưới dòng mô tả -->
    <img src="images/logo.jfif" alt="Logo" class="my-3" style="max-height: 200px;">

    <!-- Carousel bắt đầu -->
    <div id="mainCarousel" class="carousel slide mx-auto mt-4" style="max-width: 700px;" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/car1.jpg" class="d-block w-100 rounded" alt="Xe 1">
            </div>
            <div class="carousel-item">
                <img src="images/car2.png" class="d-block w-100 rounded" alt="Xe 2">
            </div>
            <div class="carousel-item">
                <img src="images/car3.webp" class="d-block w-100 rounded" alt="Xe 3">
            </div>
        </div>

        <!-- Nút chuyển trái phải -->
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Trước</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Sau</span>
        </button>
    </div>
    <!-- Carousel kết thúc -->

    <!-- Nút điều hướng -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-3 text-center">
            <a href="user/" class="btn btn-primary w-100">Truy cập người dùng</a>
        </div>
        <div class="col-md-3 text-center">
            <a href="admin/" class="btn btn-secondary w-100">Đăng nhập quản trị</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?>
