<?php
// rate_booking_process.php

require_once __DIR__ . '/../config/auth.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST['booking_id'];
    $car_id = $_POST['car_id'];
    $user_id = $_SESSION['user_id'];
    $score = intval($_POST['score']);
    $comment = trim($_POST['comment']);

    // Kiểm tra đơn có thật không và thuộc user
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ? AND status = 'completed'");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        die("Đơn không hợp lệ.");
    }

    // Kiểm tra đã đánh giá chưa
    $check = $conn->prepare("SELECT * FROM ratings WHERE booking_id = ?");
    $check->bind_param("i", $booking_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        die("Bạn đã đánh giá đơn này rồi.");
    }

    // Thêm đánh giá
    $insert = $conn->prepare("INSERT INTO ratings (booking_id, user_id, car_id, score, comment) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("iiiis", $booking_id, $user_id, $car_id, $score, $comment);
    $insert->execute();

    $_SESSION['success'] = "Đánh giá đã được gửi.";
    header("Location: my_bookings.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
