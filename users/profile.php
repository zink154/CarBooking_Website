<?php
require_once __DIR__ . '/../config/autoload_config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user']['user_id'];

// Truy vấn thông tin chi tiết từ DB (cập nhật gần nhất)
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
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['address'] ?: 'Chưa cập nhật') ?></p>
            <p><strong>Loại tài khoản:</strong> 
                <?= $user['type'] === 'admin' ? '<span class="text-danger">Admin</span>' : 'Khách hàng' ?>
            </p>

            <!-- Mở rộng: nút chỉnh sửa -->
            <div class="text-center mt-4">
                <a href="edit_profile.php" class="btn btn-warning">Cập nhật thông tin</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
