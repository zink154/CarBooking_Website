<?php
// admin/users_management.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

// Xử lý cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['type'])) {
    $user_id = intval($_POST['user_id']);
    $type = $_POST['type'];

    // Không cho admin chỉnh sửa chính mình
    if (!empty($_SESSION['admin_id']) && intval($_SESSION['admin_id']) === $user_id) {
        header('Location: users_management.php');
        exit;
    }

    // Đảm bảo giá trị type hợp lệ
    $allowed_types = ['unverified', 'verified', 'banned', 'admin'];
    if (in_array($type, $allowed_types)) {
        $stmt = $conn->prepare("UPDATE users SET type = ? WHERE user_id = ?");
        $stmt->bind_param('si', $type, $user_id);
        $stmt->execute();
    }
    header('Location: users_management.php');
    exit;
}

// Lấy danh sách người dùng
$sql = "SELECT user_id, name, email, phone, address, type FROM users";
$result = $conn->query($sql);

include __DIR__ . '/../views/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>👤 Quản lý người dùng</h2>
        <div>
            <button onclick="goBack()" class="btn btn-outline-secondary me-2">← Quay lại</button>
            <a href="dashboard.php" class="btn btn-outline-primary">Về Dashboard</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th>Thay đổi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td>
                        <?php
                            $badge = 'secondary';
                            if ($row['type'] === 'verified') $badge = 'success';
                            elseif ($row['type'] === 'banned') $badge = 'danger';
                            elseif ($row['type'] === 'admin') $badge = 'primary';
                            elseif ($row['type'] === 'unverified') $badge = 'warning';
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= $row['type'] ?></span>
                    </td>
                    <td>
                        <?php if (!empty($_SESSION['admin_id']) && intval($_SESSION['admin_id']) === intval($row['user_id'])): ?>
                            <form class="d-flex">
                                <select class="form-select form-select-sm me-2" disabled>
                                    <option value="<?= $row['type'] ?>" selected><?= $row['type'] ?></option>
                                </select>
                                <button type="button" class="btn btn-sm btn-secondary" disabled>Lưu</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                <select name="type" class="form-select form-select-sm me-2">
                                    <option value="unverified" <?= $row['type'] === 'unverified' ? 'selected' : '' ?>>unverified</option>
                                    <option value="verified" <?= $row['type'] === 'verified' ? 'selected' : '' ?>>verified</option>
                                    <option value="banned" <?= $row['type'] === 'banned' ? 'selected' : '' ?>>banned</option>
                                    <option value="admin" <?= $row['type'] === 'admin' ? 'selected' : '' ?>>admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php include __DIR__ . '/../views/footer.php'; ?>
