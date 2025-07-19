<?php
// payment.php

require_once __DIR__ . '/../config/auth.php';

if (!isset($_GET['booking_id'])) {
    echo "Thiếu mã đơn đặt xe.";
    exit();
}

$booking_id = (int)$_GET['booking_id'];

$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number, r.departure_location, r.arrival_location 
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy đơn đặt xe.";
    exit();
}

$booking = $result->fetch_assoc();

// Tính thời gian còn lại để thanh toán (3 phút từ created_at)
$created_at = strtotime($booking['created_at']);
$expire_at = $created_at + 180;

// Nếu quá hạn -> hủy booking ngay
if (time() > $expire_at) {
    $cancel_stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
    $cancel_stmt->bind_param("i", $booking_id);
    $cancel_stmt->execute();
    header("Location: cancel_booking.php?booking_id=" . $booking_id);
    exit();
}

$seconds_left = $expire_at - time();
$show_qr = isset($_POST['method']) && $_POST['method'] === 'vietqr';
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn đặt xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="mb-4">Thanh toán đơn đặt xe</h3>

            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Xe:</strong> <?= $booking['car_brand'] ?> (<?= $booking['plate_number'] ?>)</li>
                <li class="list-group-item"><strong>Tuyến:</strong> <?= $booking['departure_location'] ?> → <?= $booking['arrival_location'] ?></li>
                <li class="list-group-item"><strong>Tổng tiền:</strong> <?= number_format($booking['total_price'], 0, ',', '.') ?> VNĐ</li>
            </ul>

            <div id="countdown" class="text-danger fw-bold text-center mb-3">
                Thời gian còn lại để thanh toán: <span id="timer"></span>
            </div>

            <?php if (!$show_qr): ?>
                <form method="post">
                    <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Chọn phương thức thanh toán:</label><br>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="method" value="vietqr" required id="qrRadio">
                            <label class="form-check-label" for="qrRadio">Chuyển khoản VietQR</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="method" value="cash" id="cashRadio">
                            <label class="form-check-label" for="cashRadio">Tiền mặt khi sử dụng dịch vụ</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Tiếp tục</button>
                </form>
            <?php else: ?>
                <?php
                $amount = $booking['total_price'];
                $description = 'Dat xe ' . $booking['booking_id'];
                $bank_id = '970407'; // Techcombank
                $account_no = '1504388888';
                $template = 'compact2';
                $account_name = 'Tu Phuong Vinh';

                $qr_url = "https://img.vietqr.io/image/{$bank_id}-{$account_no}-{$template}.png"
                        . "?amount={$amount}"
                        . "&addInfo=" . urlencode($description)
                        . "&accountName=" . urlencode($account_name);
                ?>

                <div class="text-center mb-4">
                    <h5>Quét mã VietQR để thanh toán</h5>
                    <img src="<?= $qr_url ?>" alt="VietQR" class="img-fluid my-3" style="max-width: 300px;">
                    <p><strong>Nội dung chuyển khoản:</strong> <?= htmlspecialchars($description) ?></p>
                    <p><strong>Số tiền:</strong> <?= number_format($amount, 0, ',', '.') ?> VNĐ</p>
                    <p class="text-muted"><em>Vui lòng chuyển khoản đúng nội dung để được xác nhận.</em></p>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="cancel_booking.php?booking_id=<?= $booking['booking_id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Bạn có chắc muốn hủy đơn này?');">
                       Hủy đơn
                    </a>

                    <form action="payment_process.php" method="post" class="m-0">
                        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                        <input type="hidden" name="method" value="vietqr">
                        <button type="submit" class="btn btn-success">Tôi đã chuyển khoản</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Timer JS -->
<script>
    let secondsLeft = <?= $seconds_left ?>;

    function formatTime(s) {
        const m = Math.floor(s / 60);
        const sec = s % 60;
        return `${m}:${sec.toString().padStart(2, '0')}`;
    }

    const timerDisplay = document.getElementById("timer");
    timerDisplay.textContent = formatTime(secondsLeft);

    const showTime = setInterval(() => {
        secondsLeft--;
        timerDisplay.textContent = formatTime(secondsLeft);

        if (secondsLeft <= 0) {
            clearInterval(showTime);
            alert("Đã quá hạn thanh toán. Đơn sẽ bị hủy.");
            window.location.href = "cancel_booking.php?booking_id=<?= $booking['booking_id'] ?>";
        }
    }, 1000);
</script>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
