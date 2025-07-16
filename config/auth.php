<?php
require_once __DIR__ . '/session.php';

// Nếu chưa đăng nhập, chuyển về trang login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}