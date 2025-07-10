<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../pages/login.php');
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function checkAdmin() {
    if (!isAdmin()) {
        header('Location: ../pages/index.php');
        exit();
    }
}
?>
