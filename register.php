<?php
require_once 'config/db.php';
require_once 'config/session.php';

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
<h2>Đăng ký tài khoản</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <input type="text" name="name" placeholder="Họ tên" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="phone" placeholder="Số điện thoại" required><br>
    <input type="password" name="password" placeholder="Mật khẩu" required><br>
    <input type="text" name="address" placeholder="Địa chỉ"><br>
    <button type="submit">Đăng ký</button>
</form>
<?php include 'views/footer.php'; ?>
