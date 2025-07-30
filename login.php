<?php
// login.php

/**
 * Login page for TamHang Tourist users.
 * Features:
 *  - Authenticate user based on email and password.
 *  - Start a session with user information upon successful login.
 *  - Redirect user to the homepage or show error messages if login fails.
 */

require_once __DIR__ . '/config/config.php';   // Load configuration constants
require_once __DIR__ . '/config/db.php';       // Database connection
require_once __DIR__ . '/config/session.php';  // Start session handling

$error_message = ''; // Store error message to display on login failure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from form input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare and execute SQL statement to find user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using password_hash()
        if (password_verify($password, $user['password_hash'])) {
            // Store user data in session
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'name'    => $user['name'],
                'type'    => $user['type']
            ];
            
            // Add warning message if account is unverified
            if ($user['type'] === 'unverified') {
                $_SESSION['warning'] = "Tài khoản của bạn chưa được xác thực. 
                <a href='verify_notice.php' class='alert-link'>Bấm vào đây để xác thực</a>.";
            }

            // Save user ID in session for quick access
            $_SESSION['user_id'] = $user['user_id'];

            // If user is an admin, save admin ID in session
            if ($user['type'] === 'admin') {
                $_SESSION['admin_id'] = $user['user_id'];
            }

            // Redirect to homepage
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Mật khẩu không đúng!"; // Wrong password
        }
    } else {
        $error_message = "Email không tồn tại trong hệ thống!"; // Email not found
    }

    $stmt->close();
}
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include header -->

<div class="container mt-5">
    <div class="login-container bg-white p-4 rounded shadow" style="max-width: 400px; margin: auto;">
        <h1 class="text-center">Đăng nhập</h1>

        <!-- Flash messages (success/error) -->
        <?php flash_message('success'); ?>
        <?php flash_message('error'); ?>

        <!-- Show custom error message if login fails -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?= $error_message; ?></div>
        <?php endif; ?>

        <!-- Login form -->
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

        <!-- Register link -->
        <div class="text-center mt-3">
            <a href="register.php" class="text-decoration-none">Chưa có tài khoản? Đăng ký</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include footer -->
