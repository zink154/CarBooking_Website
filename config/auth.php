<?php
require_once __DIR__ . '/session.php';

// Nếu chưa đăng nhập → quay lại login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Nếu không phải admin → cấm truy cập
if ($_SESSION['user']['type'] !== 'admin') {
    die("Bạn không có quyền truy cập.");
}