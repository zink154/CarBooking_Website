<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

// Nếu chưa đăng nhập, chuyển về trang login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}