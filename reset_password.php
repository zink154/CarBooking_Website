<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if (empty($token)) {
    $error = "Token không hợp lệ!";
} else {
    // Kiểm tra token trong bảng password_resets
    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();

    if (!$reset) {
        $error = "Token không tồn tại hoặc đã được sử dụng!";
    } elseif (strtotime($reset['expires_at']) < time()) {
        $error = "Token đã hết hạn!";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (strlen($new_password) < 6) {
            $error = "Mật khẩu phải ít nhất 6 ký tự.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp.";
        } else {
            $hash = password_hash($new_password, PASSWORD_BCRYPT);

            // Cập nhật mật khẩu cho user
            $updateUser = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $updateUser->bind_param('ss', $hash, $reset['email']);
            if ($updateUser->execute()) {
                // Xóa token
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
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

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
