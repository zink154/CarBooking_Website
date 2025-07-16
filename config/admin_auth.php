<?php
require_once __DIR__ . '/session.php';

// Nếu chưa đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php"); // dùng ../ nếu file admin nằm trong thư mục con
    exit;
}

// Nếu không phải admin
if ($_SESSION['user']['type'] !== 'admin') {
    die("Bạn không có quyền truy cập.");
}