<?php
// profile.php

/**
 * This page displays the personal information of the logged-in user.
 * Features:
 *  - Fetch user details from the database (name, email, phone, address, type).
 *  - Display user information in a clean card layout.
 *  - Provide a button to edit user information (link to edit_profile.php).
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure the user is authenticated

// --- Check if user session exists ---
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// --- Get current user ID from session ---
$user_id = $_SESSION['user']['user_id'];

// --- Fetch user details from database ---
$stmt = $conn->prepare("SELECT name, email, phone, address, type FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Thông tin cá nhân</h2>

    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body">
            <!-- Display user details -->
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['address'] ?: 'Chưa cập nhật') ?></p>
            <p><strong>Loại tài khoản:</strong> 
                <?= $user['type'] === 'admin' ? '<span class="text-danger">Admin</span>' : 'Khách hàng' ?>
            </p>

            <!-- Edit profile button -->
            <div class="text-center mt-4">
                <a href="edit_profile.php" class="btn btn-warning">Cập nhật thông tin</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
