<?php
// edit_profile.php

/**
 * This page allows a logged-in user to edit their personal information (name, phone, address).
 * Features:
 *  - Fetch and display current user data.
 *  - Validate input fields (name and phone).
 *  - Update user information in the database.
 *  - Display success or error messages.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure the user is logged in

// --- Check if user session is set ---
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user']['user_id']; // Current user ID
$success = '';
$error = '';

// --- Handle form submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validate required fields
    if ($name === '' || $phone === '') {
        $error = "Vui lòng nhập đầy đủ họ tên và số điện thoại."; // "Please enter full name and phone number."
    } else {
        // Update user info
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
        if ($stmt->execute()) {
            $_SESSION['user']['name'] = $name; // Update session data if needed
            $success = "Cập nhật thông tin thành công!"; // "Profile updated successfully!"
        } else {
            $error = "Đã xảy ra lỗi khi cập nhật."; // "An error occurred while updating."
        }
    }
}

// --- Fetch current user info for pre-filling the form ---
$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container mt-5 mb-5" style="max-width: 600px;">
    <h2 class="text-center mb-4">✏️ Cập nhật thông tin cá nhân</h2>

    <!-- Display success or error messages -->
    <?php if ($success): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <!-- Profile edit form -->
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Họ tên:</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email (không thể sửa):</label>
            <input type="email" class="form-control" 
                   value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại:</label>
            <input type="text" name="phone" class="form-control" 
                   value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ:</label>
            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-yellow px-4">Lưu thay đổi</button>
            <a href="profile.php" class="btn btn-secondary ms-2">← Quay lại</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
