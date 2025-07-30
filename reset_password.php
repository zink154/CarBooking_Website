<?php
// reset_password.php

/**
 * Password Reset page.
 * Features:
 *  - Validates the password reset token from the URL.
 *  - Checks if the token exists and is not expired.
 *  - Allows the user to set a new password.
 *  - Validates new password length and confirmation match.
 *  - Hashes the new password using `password_hash()` and updates the `users` table.
 *  - Deletes the token from the `password_resets` table after use.
 *  - Displays success or error messages.
 */

require_once __DIR__ . '/config/config.php';   // Load configuration constants
require_once __DIR__ . '/config/db.php';       // Connect to database
require_once __DIR__ . '/config/session.php';  // Session management

$token = $_GET['token'] ?? '';  // Get reset token from URL
$error = '';    // Variable to store error message
$success = '';  // Variable to store success message

// Check if token is missing
if (empty($token)) {
    $error = "Token không hợp lệ!"; // Invalid token
} else {
    // Check if the token exists and is valid
    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param('s', $token);   // Bind token
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();  // Fetch record for the token

    // Validate token existence and expiration
    if (!$reset) {
        $error = "Token không tồn tại hoặc đã được sử dụng!";
    } elseif (strtotime($reset['expires_at']) < time()) {
        $error = "Token đã hết hạn!";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process new password submission
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate new password
        if (strlen($new_password) < 6) {
            $error = "Mật khẩu phải ít nhất 6 ký tự.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp.";
        } else {
            // Hash new password
            $hash = password_hash($new_password, PASSWORD_BCRYPT);

            // Update user's password in the database
            $updateUser = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $updateUser->bind_param('ss', $hash, $reset['email']);
            if ($updateUser->execute()) {
                // Delete the token after successful reset
                $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                $delete->bind_param('s', $reset['email']);
                $delete->execute();

                $success = "Mật khẩu đã được thay đổi thành công! <a href='login.php'>Đăng nhập</a>";
            } else {
                $error = "Lỗi khi cập nhật mật khẩu.";
            }
        }
    }
}
?>

<?php include __DIR__ . '/views/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Đặt lại mật khẩu</h3>

    <!-- Display success or error messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Show form only if no success and token is valid -->
    <?php if (!$success && !$error || ($error && strpos($error, 'Token') === false)): ?>
    <form method="POST">
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đặt lại mật khẩu</button>
    </form>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/views/footer.php'; ?>
