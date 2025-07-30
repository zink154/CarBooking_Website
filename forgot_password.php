<?php
// forgot_password.php

/**
 * Forgot Password page.
 * Features:
 *  - Allows users to request a password reset by entering their email address.
 *  - Validates if the email exists in the `users` table.
 *  - Generates a secure random token and stores it in the `password_resets` table.
 *  - Sets an expiration time for the token (30 minutes).
 *  - Sends a password reset link to the user's email using PHPMailer.
 *  - Displays success or error messages to the user.
 */

require_once __DIR__ . '/config/config.php';   // Load site-wide configuration
require_once __DIR__ . '/config/db.php';       // Database connection
require_once __DIR__ . '/config/session.php';  // Session management

// Include PHPMailer classes
require_once __DIR__ . '/src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/src/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/src/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = '';  // Success message holder
$error = '';    // Error message holder

// Get mail configuration from config.ini
$mailConfig = parse_ini_file(__DIR__ . '/config.ini', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);  // Get email from user input

    // Check if email exists in the users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // No user with that email
        $error = "Email không tồn tại trong hệ thống!";
    } else {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', time() + 1800); // 30 minutes expiry

        // Delete any existing token for this email
        $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $delete->bind_param('s', $email);
        $delete->execute();

        // Insert new token into password_resets
        $stmt2 = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt2->bind_param('sss', $email, $token, $expires_at);
        $stmt2->execute();

        // Create the reset password link
        $resetLink = BASE_URL . "/reset_password.php?token=" . $token;

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();  // Use SMTP
            $mail->Host       = $mailConfig['mail']['host'];       // SMTP server
            $mail->SMTPAuth   = true;                              // Enable authentication
            $mail->Username   = $mailConfig['mail']['username'];   // SMTP username
            $mail->Password   = $mailConfig['mail']['app_password']; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Encryption
            $mail->Port       = $mailConfig['mail']['port'];       // SMTP port
            $mail->CharSet    = 'UTF-8';                           // Set charset

            // Email sender and recipient
            $mail->setFrom($mailConfig['mail']['username'], $mailConfig['mail']['from_name']);
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Yêu cầu đặt lại mật khẩu";
            $mail->Body    = "Chào bạn,<br><br>
                              Vui lòng click vào liên kết dưới đây để đặt lại mật khẩu của bạn (hết hạn sau 30 phút):<br>
                              <a href='$resetLink'>$resetLink</a><br><br>
                              Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.";

            // Send the email
            $mail->send();
            $success = "Email khôi phục đã được gửi đến <strong>$email</strong>!";
        } catch (Exception $e) {
            // Catch email sending errors
            $error = "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        }
    }
}
?>

<?php include __DIR__ . '/views/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Quên mật khẩu</h3>

    <!-- Display success or error messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Password reset request form -->
    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Nhập Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Gửi yêu cầu</button>
    </form>
</div>
<?php include __DIR__ . '/views/footer.php'; ?>
