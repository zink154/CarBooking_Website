<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

// Chỉ cho phép người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Lấy mật khẩu hiện tại của user
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        $error = "Mật khẩu cũ không chính xác.";
    } elseif (strlen($new_password) < 6) {
        $error = "Mật khẩu mới phải ít nhất 6 ký tự.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        // Hash mật khẩu mới
        $hash = password_hash($new_password, PASSWORD_BCRYPT);

        // Cập nhật vào DB
        $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $update->bind_param('si', $hash, $user_id);
        if ($update->execute()) {
            $success = "Mật khẩu đã được đổi thành công!";
        } else {
            $error = "Lỗi khi đổi mật khẩu.";
        }
    }
}
?>

<?php include __DIR__ . '/views/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Đổi mật khẩu</h3>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="old_password" class="form-label">Mật khẩu cũ:</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật khẩu mới:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới:</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đổi mật khẩu</button>
    </form>
</div>
<?php include __DIR__ . '/views/footer.php'; ?>
