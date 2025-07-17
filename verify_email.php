<?php
require_once __DIR__ . '/config/autoload_config.php';

$token = $_GET['token'] ?? null;

if (!$token) {
    die("Không tìm thấy mã xác thực.");
}

// Tìm user_id và thời gian tạo token
$stmt = $conn->prepare("SELECT user_id, created_at FROM email_verifications WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Mã xác thực không hợp lệ hoặc đã được sử dụng.");
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$created_at = strtotime($row['created_at']);
$now = time();

// Kiểm tra hết hạn (5 phút)
if (($now - $created_at) > 300) {
    die("Mã xác thực đã hết hạn. Vui lòng yêu cầu gửi lại email xác thực.");
}

// Cập nhật trạng thái user → 'verified'
$update = $conn->prepare("UPDATE users SET type = 'verified' WHERE user_id = ?");
$update->bind_param("i", $user_id);
$update->execute();

// Ghi log xác thực (IP + thời gian)
$ip = $_SERVER['REMOTE_ADDR'];
$log = $conn->prepare("INSERT INTO email_verification_logs (user_id, ip_address) VALUES (?, ?)");
$log->bind_param("is", $user_id, $ip);
$log->execute();

// Xoá token để tránh xác thực lại
$delete = $conn->prepare("DELETE FROM email_verifications WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();

// Thông báo và chuyển hướng
$_SESSION['success'] = "Xác thực thành công! Bạn có thể đăng nhập.";
header("Location: login.php");
exit;
?>

<?php include 'views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf3ea;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="card">
        <h4 class="text-success mb-3">✅ Xác thực thành công!</h4>
        <p>Bạn có thể <a href="login.php">đăng nhập</a> ngay bây giờ.</p>
    </div>
</body>
</html>

<?php include 'views/footer.php'; ?>