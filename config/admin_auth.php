<?php
// admin_auth.php

require_once __DIR__ . '/config.php';   // General configuration (constants, paths, etc.)
require_once __DIR__ . '/session.php';  // Start session and handle session logic
require_once __DIR__ . '/db.php';       // Database connection (if needed)

// --- Check if user is logged in ---
if (!isset($_SESSION['user'])) {
    // If the user is not logged in, redirect them to the login page.
    // "../" is used because admin files are in a subdirectory.
    header("Location: ../login.php");
    exit;
}

// --- Check if the user is an admin ---
if ($_SESSION['user']['type'] !== 'admin') {
    // If the user is not an admin, show an access denied message.
    die("Bạn không có quyền truy cập."); // "You do not have permission to access this page."
}
