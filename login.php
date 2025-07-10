<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'type' => $user['type']
            ];

            // Chuyển hướng theo vai trò
            if ($user['type'] === 'admin') {
                header("Location: /admin/");
            } else {
                header("Location: /user/");
            }
            exit;
        } else {
            $error_message = "Mật khẩu không đúng!";
        }
    } else {
        $error_message = "Email không tồn tại trong hệ thống!";
    }

    $stmt->close();
}
?>

<?php include __DIR__ . '/views/header.php'; ?>

<div class="container mt-5">
    <div class="login-container bg-white p-4 rounded shadow" style="max-width: 400px; margin: auto;">
        <h1 class="text-center">Đăng nhập</h1>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu:</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập mật khẩu.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>

        <div class="text-center mt-3">
            <a href="register.php" class="text-decoration-none">Chưa có tài khoản? Đăng ký</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?>
