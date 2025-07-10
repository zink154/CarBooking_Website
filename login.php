<?php
require_once 'config/db.php';
require_once 'config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'type' => $user['type']
            ];
            header("Location: index.php");
            exit;
        }
    }
    $error = "Sai email hoặc mật khẩu.";
}
?>

<!-- HTML FORM -->
<?php include 'views/header.php'; ?>
<h2>Đăng nhập</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Mật khẩu" required><br>
    <button type="submit">Đăng nhập</button>
</form>
<?php include 'views/footer.php'; ?>
