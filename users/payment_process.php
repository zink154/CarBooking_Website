<?php
// payment_process.php

/**
 * This script handles the payment process for a booking.
 * Features:
 *  - Validate the payment method (only 'vietqr' or 'cash').
 *  - Verify that the booking belongs to the logged-in user.
 *  - Prevent duplicate payments for the same booking.
 *  - Insert a new payment record into the `payments` table.
 *  - Update booking status to 'processing' if payment is made via VietQR.
 *  - Redirect to the payment success page upon success.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure user is logged in

// --- Process only POST requests ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = (int)$_POST['booking_id'];  // Booking ID to pay for
    $method = $_POST['method'];               // Payment method (vietqr or cash)
    $user_id = $_SESSION['user_id'];          // Current logged-in user

    // --- Validate allowed payment methods ---
    $allowed_methods = ['vietqr', 'cash'];
    if (!in_array($method, $allowed_methods)) {
        die("Phương thức thanh toán không hợp lệ."); // "Invalid payment method."
    }

    // --- Check if the booking is valid and belongs to the user ---
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    if (!$booking) {
        die("Đơn đặt xe không hợp lệ."); // "Invalid booking."
    }

    // --- Check if the booking has already been paid ---
    $check = $conn->prepare("SELECT * FROM payments WHERE booking_id = ?");
    $check->bind_param("i", $booking_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        die("Đơn này đã được thanh toán."); // "This booking has already been paid."
    }

    // --- Determine payment status ---
    // 'vietqr' payments are marked as 'paid', 'cash' as 'unpaid' (to be paid later)
    $status = ($method === 'vietqr') ? 'paid' : 'unpaid';

    // --- Insert new payment record ---
    $insert = $conn->prepare("INSERT INTO payments (booking_id, method, status) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $booking_id, $method, $status);

    if ($insert->execute()) {
        // --- Update booking status if payment is completed (VietQR) ---
        if ($method === 'vietqr') {
            $update = $conn->prepare("UPDATE bookings SET status = 'processing' WHERE booking_id = ?");
            $update->bind_param("i", $booking_id);
            $update->execute();
        }

        // --- Redirect to payment success page ---
        header("Location: payment_success.php?booking_id=" . $booking_id);
        exit();
    } else {
        die("Có lỗi xảy ra khi xử lý thanh toán."); // "An error occurred while processing payment."
    }
}
?>
