<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
function checkLogin($redirect = 'login.php') {
    if (!isset($_SESSION['user'])) {
        header("Location: $redirect");
        exit();
    }
}

// Kiểm tra admin
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['type'] === 'admin';
}

// Chặn nếu không phải admin
function checkAdmin($redirect = 'index.php') {
    if (!isAdmin()) {
        header("Location: $redirect");
        exit();
    }
}
