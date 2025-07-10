<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';
?>

<?php include __DIR__ . '/views/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Chào mừng đến với hệ thống Đặt Xe</h1>
    <p class="text-center">Hệ thống giúp bạn đặt xe nhanh chóng, tiện lợi, và an toàn.</p>

    <div class="row justify-content-center mt-4">
        <div class="col-md-3 text-center">
            <a href="user/" class="btn btn-primary btn-block">Truy cập người dùng</a>
        </div>
        <div class="col-md-3 text-center">
            <a href="admin/" class="btn btn-secondary btn-block">Đăng nhập quản trị</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?>
