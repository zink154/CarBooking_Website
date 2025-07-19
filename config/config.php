<?php
// config.php

// Định nghĩa các hằng số
define('BASE_URL', 'http://localhost/CarBooking_Website');
define('DB_HOST', 'localhost');
define('DB_NAME', 'car_booking');
define('DB_USER', 'root');
define('DB_PASS', '');

date_default_timezone_set('Asia/Ho_Chi_Minh');
// Thiết lập kết nối cơ sở dữ liệu
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>
