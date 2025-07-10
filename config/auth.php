<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php"); // Chuyển hướng đến trang login nếu không phải admin
    exit;
}
?>
