<?php
// rate_booking.php

require_once __DIR__ . '/../config/auth.php';

if (!isset($_GET['booking_id'])) {
    echo "Thiếu mã đơn.";
    exit();
}

$booking_id = (int)$_GET['booking_id'];
$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn đặt xe đã hoàn tất
$stmt = $conn->prepare("
    SELECT b.*, c.car_brand, c.plate_number 
    FROM bookings b
    JOIN cars c ON b.car_id = c.car_id
    WHERE b.booking_id = ? AND b.user_id = ? AND b.status = 'completed'
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy đơn hoàn tất để đánh giá.";
    exit();
}

$booking = $result->fetch_assoc();
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đánh giá chuyến đi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star-rating {
            direction: rtl;
            font-size: 1.5rem;
            unicode-bidi: bidi-override;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            color: #ccc;
            cursor: pointer;
            font-size: 2rem;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f39c12;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">
                Đánh giá xe: <?= htmlspecialchars($booking['car_brand']) ?> (<?= htmlspecialchars($booking['plate_number']) ?>)
            </h3>

            <form action="rate_booking_process.php" method="POST">
                <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
                <input type="hidden" name="car_id" value="<?= $booking['car_id'] ?>">

                <div class="mb-4 text-center">
                    <label class="form-label d-block">Chọn số sao:</label>
                    <div class="star-rating mx-auto">
                        <input type="radio" name="score" id="star5" value="5" required><label for="star5">★</label>
                        <input type="radio" name="score" id="star4" value="4"><label for="star4">★</label>
                        <input type="radio" name="score" id="star3" value="3"><label for="star3">★</label>
                        <input type="radio" name="score" id="star2" value="2"><label for="star2">★</label>
                        <input type="radio" name="score" id="star1" value="1"><label for="star1">★</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="comment" class="form-label">Nhận xét:</label>
                    <textarea name="comment" id="comment" rows="4" class="form-control" placeholder="Viết nhận xét của bạn..." required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Gửi đánh giá</button>
                    <a href="my_bookings.php" class="btn btn-outline-secondary px-4">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
