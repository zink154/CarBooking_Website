<?php
// verify_email.php

/**
 * This script handles email verification.
 * Features:
 *  - Validate the verification token.
 *  - Check if the token has expired (default 1-minute expiration).
 *  - Update the user status from 'unverified' to 'verified'.
 *  - Log the verification details (IP address, timestamp).
 *  - Remove the used token from the database.
 *  - Redirect the user with a success message.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Load configs, session, and DB

$token = $_GET['token'] ?? null;

if (!$token) {
    die("Không tìm thấy mã xác thực."); // Token not found
}

// --- Query token from email_verifications table ---
$stmt = $conn->prepare("SELECT user_id, created_at FROM email_verifications WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Mã xác thực không hợp lệ hoặc đã được sử dụng."); // Invalid or used token
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$created_at = strtotime($row['created_at']);
$now = time();

// --- Check if token is expired (60 seconds) ---
if (($now - $created_at) > 60) {
    $delete = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();

    die("Liên kết xác thực đã hết hạn. Vui lòng <a href='verify_notice.php?auto_resend=1'>gửi lại</a>.");
}

// --- Update user status to 'verified' ---
$update = $conn->prepare("UPDATE users SET type = 'verified' WHERE user_id = ?");
$update->bind_param("i", $user_id);
$update->execute();

// --- Log verification details ---
$ip = $_SERVER['REMOTE_ADDR'];
$log = $conn->prepare("INSERT INTO email_verification_logs (user_id, ip_address, verified_at) VALUES (?, ?, NOW())");
$log->bind_param("is", $user_id, $ip);
$log->execute();

// --- Delete the token after verification ---
$delete = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();

// --- Set success flag and update session ---
$_SESSION['verified_success'] = true;
$_SESSION['user']['type'] = 'verified'; // Update session to hide verification warnings

// --- Redirect back to verify_notice with success flag ---
header("Location: verify_notice.php?verified=1&redirect=home");
exit;
