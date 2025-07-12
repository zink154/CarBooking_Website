<?php
// Đảm bảo session đã được khởi tạo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu chưa đăng nhập → quay lại login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Nếu không phải admin → cấm truy cập
if ($_SESSION['user']['type'] !== 'admin') {
    die("Bạn không có quyền truy cập.");
}