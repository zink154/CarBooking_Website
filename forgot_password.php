<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

// Gọi PHPMailer
require_once __DIR__ . '/src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/src/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/src/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = '';
$error = '';

// Lấy cấu hình mail từ file config.ini
$mailConfig = parse_ini_file(__DIR__ . '/config.ini', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Kiểm tra email tồn tại trong hệ thống
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Email không tồn tại trong hệ thống!";
    } else {
        // Tạo token
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', time() + 1800); // 30 phút

        // Xóa token cũ
        $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $delete->bind_param('s', $email);
        $delete->execute();

        // Lưu token mới
        $stmt2 = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt2->bind_param('sss', $email, $token, $expires_at);
        $stmt2->execute();

        // Link đặt lại mật khẩu
        $resetLink = BASE_URL . "/reset_password.php?token=" . $token;

        // Gửi email bằng PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $mailConfig['mail']['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['mail']['username'];
            $mail->Password   = $mailConfig['mail']['app_password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $mailConfig['mail']['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($mailConfig['mail']['username'], $mailConfig['mail']['from_name']);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Yêu cầu đặt lại mật khẩu";
            $mail->Body    = "Chào bạn,<br><br>
                              Vui lòng click vào liên kết dưới đây để đặt lại mật khẩu của bạn (hết hạn sau 30 phút):<br>
                              <a href='$resetLink'>$resetLink</a><br><br>
                              Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.";

            $mail->send();
            $success = "Email khôi phục đã được gửi đến <strong>$email</strong>!";
        } catch (Exception $e) {
            $error = "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        }
    }
}
?>

<?php include __DIR__ . '/views/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Quên mật khẩu</h3>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Nhập Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Gửi yêu cầu</button>
    </form>
</div>
<?php include __DIR__ . '/views/footer.php'; ?>
