<?php
// Đảm bảo session đã khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Lấy ID người dùng hiện tại
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Lấy vai trò người dùng hiện tại
 */
function getUserRole() {
    return $_SESSION['role'] ?? 'guest';
}

/**
 * Lấy tên người dùng (nếu đã lưu trong session)
 */
function getUserName() {
    return $_SESSION['name'] ?? null;
}

/**
 * Kiểm tra người dùng đã đăng nhập chưa
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Kiểm tra có phải admin không
 */
function isAdminUser() {
    return getUserRole() === 'admin';
}
