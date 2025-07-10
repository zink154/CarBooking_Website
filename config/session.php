<?php
// Chỉ khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu chưa đăng nhập thì chuyển về trang đăng nhập người dùng
function checkLogin($redirect = 'login.php') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: $redirect");
        exit();
    }
}

// Kiểm tra có phải admin hay không
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Nếu không phải admin thì chuyển hướng
function checkAdmin($redirect = 'index.php') {
    if (!isAdmin()) {
        header("Location: $redirect");
        exit();
    }
}
