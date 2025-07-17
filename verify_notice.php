<?php
require_once __DIR__ . '/config/autoload_config.php';
?>

<?php include 'views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef1f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .verify-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 450px;
        }
        #countdown {
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <h4 class="mb-3">📩 Kiểm tra email của bạn</h4>
        <p>Chúng tôi đã gửi cho bạn một liên kết xác thực.</p>
        <p>Liên kết sẽ hết hạn sau: <span id="countdown">05:00</span></p>
        <p class="mt-3">Không nhận được email? <a href="resend_verification.php">Gửi lại</a></p>
    </div>

    <script>
        let timeLeft = 300;
        const countdown = document.getElementById('countdown');

        const timer = setInterval(() => {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            countdown.textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            timeLeft--;

            if (timeLeft < 0) {
                clearInterval(timer);
                countdown.textContent = "Hết hạn";
            }
        }, 1000);
    </script>
</body>
</html>

<?php include 'views/footer.php'; ?>