<?php
require_once __DIR__ . '/config/autoload_config.php';
require_once __DIR__ . '/src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/src/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/src/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mailConfig = parse_ini_file(__DIR__ . '/config.ini', true);

// ✅ Xử lý trước khi có bất kỳ HTML hoặc include nào
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Không tìm thấy thông tin người dùng.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Truy vấn user
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
if ($user['type'] !== 'unverified') {
    $_SESSION['error'] = "Tài khoản đã xác thực hoặc không hợp lệ.";
    header("Location: login.php");
    exit;
}

$name = $user['name'];
$email = $user['email'];

// Xoá token cũ và tạo token mới
$stmt = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$token = bin2hex(random_bytes(32));
$stmt = $conn->prepare("INSERT INTO email_verifications (user_id, token, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $user_id, $token);
$stmt->execute();

$verification_link = "http://localhost/CarBooking_Website/verify_email.php?token=$token";

// Gửi email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $mailConfig['mail']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $mailConfig['mail']['username'];
    $mail->Password = $mailConfig['mail']['app_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $mailConfig['mail']['port'];
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($mailConfig['mail']['username'], $mailConfig['mail']['from_name']);
    $mail->addAddress($email, $name);
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

// Redirect lại verify_notice.php sau khi xử lý xong
header("Location: verify_notice.php");
exit;
