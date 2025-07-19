<?php
// resend_verification.php

require_once __DIR__ . '/config/autoload_config.php';              // Load configuration, DB connection, and session
require_once __DIR__ . '/src/PHPMailer-master/src/PHPMailer.php';  // PHPMailer class
require_once __DIR__ . '/src/PHPMailer-master/src/SMTP.php';       // SMTP class for PHPMailer
require_once __DIR__ . '/src/PHPMailer-master/src/Exception.php';  // Exception handling for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load mail configuration from config.ini
$mailConfig = parse_ini_file(__DIR__ . '/config.ini', true);

// --- Pre-check: user session must exist ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Không tìm thấy thông tin người dùng."; // User not found
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// --- Fetch user info ---
$stmt = $conn->prepare("SELECT name, email, type FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Không tìm thấy tài khoản.";
    header("Location: register.php");
    exit;
}

$user = $result->fetch_assoc();

// --- Ensure the user is still unverified ---
if ($user['type'] !== 'unverified') {
    $_SESSION['error'] = "Tài khoản đã xác thực hoặc không hợp lệ.";
    header("Location: login.php");
    exit;
}

$name = $user['name'];
$email = $user['email'];

// --- Delete old verification token and create a new one ---
$stmt = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$token = bin2hex(random_bytes(32)); // Generate a secure random token
$stmt = $conn->prepare("INSERT INTO email_verifications (user_id, token, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $user_id, $token);
$stmt->execute();

// Create the verification link
$verification_link = "http://localhost/CarBooking_Website/verify_email.php?token=$token";

// --- Send email using PHPMailer ---
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $mailConfig['mail']['host'];          // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $mailConfig['mail']['username'];  // SMTP username
    $mail->Password = $mailConfig['mail']['app_password']; // SMTP password or app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
    $mail->Port = $mailConfig['mail']['port'];
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($mailConfig['mail']['username'], $mailConfig['mail']['from_name']); // Sender
    $mail->addAddress($email, $name); // Recipient
    $mail->isHTML(true);
    $mail->Subject = 'Xác thực tài khoản';
    $mail->Body = "Xin chào <strong>$name</strong>,<br><br>
        Đây là liên kết xác thực của bạn:<br>
        <a href='$verification_link'>$verification_link</a><br><br>
        Liên kết chỉ có hiệu lực trong vòng 1 phút.<br><br>
        Trân trọng.";

    $mail->send();
    $_SESSION['success'] = "Đã gửi lại liên kết xác thực. Vui lòng kiểm tra email.";
} catch (Exception $e) {
    $_SESSION['error'] = "Không thể gửi email: {$mail->ErrorInfo}";
}

// --- Redirect back to verification notice page ---
header("Location: verify_notice.php");
exit;
