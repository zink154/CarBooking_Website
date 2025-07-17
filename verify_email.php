<?php
// verify_email.php

require_once __DIR__ . '/config/autoload_config.php';

$token = $_GET['token'] ?? null;

if (!$token) {
    die("Không tìm thấy mã xác thực.");
}

// Truy vấn token và thời gian tạo
$stmt = $conn->prepare("SELECT user_id, created_at FROM email_verifications WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Mã xác thực không hợp lệ hoặc đã được sử dụng.");
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$created_at = strtotime($row['created_at']);
$now = time();

// ⏱️ Kiểm tra hết hạn sau 60 giây (1 phút)
if (($now - $created_at) > 60) {
    $delete = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();

    die("Liên kết xác thực đã hết hạn. Vui lòng <a href='verify_notice.php?auto_resend=1'>gửi lại</a>.");
}

// Cập nhật user thành 'verified'
$update = $conn->prepare("UPDATE users SET type = 'verified' WHERE user_id = ?");
$update->bind_param("i", $user_id);
$update->execute();

// Ghi log xác thực
$ip = $_SERVER['REMOTE_ADDR'];
$log = $conn->prepare("INSERT INTO email_verification_logs (user_id, ip_address, verified_at) VALUES (?, ?, NOW())");
$log->bind_param("is", $user_id, $ip);
$log->execute();

// Xoá token sau xác thực
$delete = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();

// Đánh dấu thành công
$_SESSION['verified_success'] = true;
$_SESSION['user']['type'] = 'verified'; // cập nhật session để ẩn cảnh báo

// Chuyển về verify_notice, truyền thêm ?redirect=home để nó về trang chủ sau đó
header("Location: verify_notice.php?verified=1&redirect=home");
exit;