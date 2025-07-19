<?php
// rate_booking_process.php

/**
 * This script handles the submission of a booking rating by the user.
 * Features:
 *  - Ensure the booking belongs to the logged-in user and is completed.
 *  - Check if the booking has not been rated before (avoid duplicates).
 *  - Insert a new rating into the `ratings` table.
 *  - Redirect the user back to their booking history with a success message.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure the user is authenticated

// --- Process only POST requests ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST['booking_id'];
    $car_id = $_POST['car_id'];
    $user_id = $_SESSION['user_id'];
    $score = intval($_POST['score']);
    $comment = trim($_POST['comment']);

    // --- Validate that booking exists and belongs to the user ---
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ? AND status = 'completed'");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        die("Đơn không hợp lệ."); // "Invalid booking."
    }

    // --- Check if this booking has already been rated ---
    $check = $conn->prepare("SELECT * FROM ratings WHERE booking_id = ?");
    $check->bind_param("i", $booking_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        die("Bạn đã đánh giá đơn này rồi."); // "You have already rated this booking."
    }

    // --- Insert the rating ---
    $insert = $conn->prepare("INSERT INTO ratings (booking_id, user_id, car_id, score, comment) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("iiiis", $booking_id, $user_id, $car_id, $score, $comment);
    $insert->execute();

    // --- Set success message and redirect ---
    $_SESSION['success'] = "Đánh giá đã được gửi."; // "Your rating has been submitted."
    header("Location: my_bookings.php");
    exit();
} else {
    // If request is not POST, redirect to homepage
    header("Location: index.php");
    exit();
}
