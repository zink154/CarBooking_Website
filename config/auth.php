<?php
// auth.php

require_once __DIR__ . '/config.php';   // Load general configuration (constants, settings)
require_once __DIR__ . '/session.php';  // Start or resume session to access user data
require_once __DIR__ . '/db.php';       // Database connection (if needed for verification)

// --- Check if the user is logged in ---
if (!isset($_SESSION['user'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: " . BASE_URL . "/login.php");
    exit; // Stop script execution after redirection
}
