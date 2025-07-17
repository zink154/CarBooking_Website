<?php
require_once __DIR__ . '/config/autoload_config.php';

// ✅ Gửi mail trước khi in ra bất kỳ thứ gì
if (isset($_GET['auto_resend']) && $_GET['auto_resend'] == 1) {
    include __DIR__ . '/resend_verification.php';
    exit;
}

include 'views/header.php';

// Nếu vừa xác thực xong
$justVerified = isset($_SESSION['verified_success']) && $_GET['verified'] == 1;
$redirectTo = ($_GET['redirect'] ?? '') === 'home' ? 'index.php' : 'login.php';
if ($justVerified) {
    unset($_SESSION['verified_success']);
}
?>

<style>
    .verify-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 180px); 
        background-color: #eef1f5;
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

<div class="verify-wrapper">
    <?php if ($justVerified): ?>
        <div class="verify-card">
            <h4 class="text-success mb-3">Xác thực thành công!</h4>
            <p>Bạn sẽ được chuyển đến trang chủ sau <span id="countdown">5</span> giây.</p>
            <p>Nếu không chuyển, <a href="index.php">bấm vào đây</a>.</p>
        </div>
        <script>
            let timeLeft = 5;
            const countdown = document.getElementById('countdown');
            const timer = setInterval(() => {
                countdown.textContent = timeLeft;
                timeLeft--;
                if (timeLeft < 0) {
                    clearInterval(timer);
                    window.location.href = "index.php";
                }
            }, 1000);
        </script>
    <?php else: ?>
        <div class="verify-card">
            <h4 class="mb-3">📩 Kiểm tra email của bạn</h4>
            <p>Chúng tôi đã gửi cho bạn một liên kết xác thực.</p>
            <p>Liên kết sẽ hết hạn sau: <span id="countdown">01:00</span></p>
            <form method="post" action="resend_verification.php" class="mt-3">
                <button type="submit" class="btn btn-link p-0">Gửi lại</button>
            </form>
        </div>
        <script>
            let timeLeft = 60;
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
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>