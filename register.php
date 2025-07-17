<?php
// register.php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db.php';


// Load cấu hình từ config.ini
$mailConfig = parse_ini_file(__DIR__ . '/config.ini', true);

// Thêm PHPMailer
require_once __DIR__ . '/src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/src/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/src/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $type = 'unverified';

    if ($password_raw !== $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp.";
    } else {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Email đã được sử dụng.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, type) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $phone, $password, $type);
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;

                // Tạo token xác thực
                $token = bin2hex(random_bytes(32));
                $stmt_token = $conn->prepare("INSERT INTO email_verifications (user_id, token) VALUES (?, ?)");
                $stmt_token->bind_param("is", $user_id, $token);
                $stmt_token->execute();

                $verification_link = "http://localhost/CarBooking_Website/verify_email.php?token=$token";

                // Gửi email xác thực
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = $mailConfig['mail']['host'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $mailConfig['mail']['username'];
                    $mail->Password   = $mailConfig['mail']['app_password'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $mailConfig['mail']['port'];

                    $mail->CharSet = 'UTF-8';

                    $mail->setFrom($mailConfig['mail']['username'], $mailConfig['mail']['from_name']);
                    $mail->addAddress($email, $name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Xác thực tài khoản đặt xe';
                    $mail->Body    = "Xin chào <strong>$name</strong>,<br><br>
                        Vui lòng xác thực tài khoản bằng cách nhấn vào liên kết sau:<br>
                        <a href='$verification_link'>$verification_link</a><br><br>
                        Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.";

                    $mail->send();
                    $_SESSION['success'] = "Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.";
                    header("Location: " . BASE_URL . "/verify_notice.php");
                    exit;
                } catch (Exception $e) {
                    $error = "Gửi email xác thực thất bại: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Lỗi khi đăng ký tài khoản.";
            }
        }
    }
}
?>

<!-- HTML FORM -->
<?php include 'views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
        }
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 60px auto;
        }

        .card {
            width: 100%;
            max-width: 400px; /* hoặc 360px nếu bạn muốn rộng chút */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: 600;
            font-size: 20px;
        }
        .form-control {
            border-radius: 6px;
            font-size: 14px;
            padding: 8px 12px;
        }
        button.btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
        }
        button.btn-primary:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>
    <div class="container register-container">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">TẠO TÀI KHOẢN</h3>
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và Tên:</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
                <p class="mt-3 text-center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</body>
</html>

<?php include 'views/footer.php'; ?>
