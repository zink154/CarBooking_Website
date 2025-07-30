<?php
// change_password.php

/**
 * Change Password page for logged-in users.
 * Features:
 *  - Requires the user to be logged in (checks `$_SESSION['user_id']`).
 *  - Validates the old password against the current password hash in the database.
 *  - Ensures the new password meets the minimum length (6 characters).
 *  - Checks that the new password matches the confirmation field.
 *  - Hashes the new password with `password_hash()` before updating.
 *  - Updates the user's password in the `users` table.
 *  - Displays success or error messages to the user.
 */

require_once __DIR__ . '/config/config.php';   // Load configuration constants
require_once __DIR__ . '/config/db.php';       // Database connection
require_once __DIR__ . '/config/session.php';  // Session management

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['user_id']; // Get current logged-in user ID
$error = '';   // Variable to store error messages
$success = ''; // Variable to store success messages

// If the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'] ?? '';         // Old password from user input
    $new_password = $_POST['new_password'] ?? '';         // New password from user input
    $confirm_password = $_POST['confirm_password'] ?? ''; // Password confirmation

    // Get current password hash from the database
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);    // Bind user_id as integer
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();      // Fetch user data

    // Validate old password
    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        $error = "Mật khẩu cũ không chính xác.";
    } 
    // Check new password length
    elseif (strlen($new_password) < 6) {
        $error = "Mật khẩu mới phải ít nhất 6 ký tự.";
    } 
    // Check if new password matches the confirmation
    elseif ($new_password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } 
    else {
        // Hash the new password
        $hash = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the user's password in the database
        $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $update->bind_param('si', $hash, $user_id);
        if ($update->execute()) {
            $success = "Mật khẩu đã được đổi thành công!"; // Success message
        } else {
            $error = "Lỗi khi đổi mật khẩu.";              // Error if DB update fails
        }
    }
}
?>

<?php include __DIR__ . '/views/header.php'; ?>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Đổi mật khẩu</h3>

    <!-- Display success or error message -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Password change form -->
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
