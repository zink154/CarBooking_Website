<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = trim($_POST['address']);
    $type = 'unverified';

    // Kiểm tra email đã tồn tại chưa
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $error = "Email đã được sử dụng.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, address, type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $password, $address, $type);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Lỗi khi đăng ký.";
        }
    }
}
?>

<!-- HTML FORM -->
<?php include 'views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
        }
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 60px auto;
        }

        .card {
            width: 100%;
            max-width: 400px; /* hoặc 360px nếu bạn muốn rộng chút */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: 600;
            font-size: 20px;
        }
        .form-control {
            border-radius: 6px;
            font-size: 14px;
            padding: 8px 12px;
        }
        button.btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
        }
        button.btn-primary:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>
    <div class="container register-container">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">TẠO TÀI KHOẢN</h3>
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">HỌ TÊN</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">EMAIL</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">SỐ ĐIỆN THOẠI</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">MẬT KHẨU</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">XÁC NHẬN MẬT KHẨU</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
                <p class="mt-3 text-center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</body>
</html>

<?php include 'views/footer.php'; ?>
