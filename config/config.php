<?php
// config.php

/**
 * This file defines the main configuration settings for the CarBooking Website.
 * It includes:
 *  - Base URL of the website.
 *  - Database connection parameters (host, name, user, password).
 *  - Timezone configuration.
 *  - Establishing a PDO database connection.
 */

// --- Define constants ---
define('BASE_URL', 'http://localhost/CarBooking_Website'); // Base URL for the application
define('DB_HOST', 'localhost');   // Database host (usually localhost)
define('DB_NAME', 'car_booking'); // Database name
define('DB_USER', 'root');        // Database username
define('DB_PASS', '');            // Database password

// --- Set the default timezone ---
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set to Vietnam time zone

// --- Database connection setup using PDO ---
try {
    // Create a new PDO instance with UTF-8 encoding
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, terminate script and show error
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
