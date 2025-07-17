<?php
require_once __DIR__ . '/config/autoload_config.php';

// PHPMailer
require_once __DIR__ . '/src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/src/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/src/PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mailConfig = parse_ini_file(__DIR__ . '/config/config.ini', true);
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Kiểm tra user tồn tại và chưa xác thực
    $stmt = $conn->prepare("SELECT user_id, name, type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Không tìm thấy tài khoản với email này.";
    } else {
        $user = $result->fetch_assoc();
        if ($user['type'] !== 'unverified') {
            $error = "Tài khoản này đã được xác thực hoặc bị chặn.";
        } else {
            $user_id = $user['user_id'];
            $name = $user['name'];

            // Kiểm tra giới hạn thời gian gửi lại
            $stmt_check_time = $conn->prepare("SELECT last_sent_at FROM email_verifications WHERE user_id = ?");
            $stmt_check_time->bind_param("i", $user_id);
            $stmt_check_time->execute();
            $res_time = $stmt_check_time->get_result();

            if ($res_time->num_rows > 0) {
                $last_sent = strtotime($res_time->fetch_assoc()['last_sent_at']);
                $now = time();
                if (($now - $last_sent) < 180) { // 3 phút
                    $wait = 180 - ($now - $last_sent);
                    $error = "Bạn vừa yêu cầu gửi lại gần đây. Vui lòng thử lại sau $wait giây.";
                }
            }

            if (!$error) {
                // Xoá token cũ nếu có
                $conn->query("DELETE FROM email_verifications WHERE user_id = $user_id");

                // Tạo token mới
                $token = bin2hex(random_bytes(32));
                $stmt_token = $conn->prepare("INSERT INTO email_verifications (user_id, token, last_sent_at) VALUES (?, ?, NOW())");
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

                    $mail->setFrom($mailConfig['mail']['username'], $mailConfig['mail']['from_name']);
                    $mail->addAddress($email, $name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Gửi lại email xác thực tài khoản';
                    $mail->Body    = "Xin chào <strong>$name</strong>,<br><br>
                        Đây là liên kết xác thực mới của bạn:<br>
                        <a href='$verification_link'>$verification_link</a><br><br>
                        Liên kết này sẽ hết hạn sau 5 phút.";

                    $mail->send();
                    $success = "Email xác thực đã được gửi lại thành công!";
                } catch (Exception $e) {
                    $error = "Gửi email thất bại: {$mail->ErrorInfo}";
                }
            }
        }
    }
}
?>

<?php include 'views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Gửi lại xác thực</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            padding: 60px 0;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h4 class="mb-3">🔄 Gửi lại email xác thực</h4>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email đăng ký:</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="nhap@example.com">
            </div>
            <button type="submit" class="btn btn-primary w-100">Gửi lại liên kết xác thực</button>
        </form>
    </div>
</body>
</html>

<?php include 'views/footer.php'; ?>