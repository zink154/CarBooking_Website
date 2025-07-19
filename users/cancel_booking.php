<?php
// cancel_booking.php

/**
 * This script allows a user to cancel their booking if:
 *  - The booking has not been paid yet.
 *  - The booking status is still 'booked'.
 * 
 * Workflow:
 *  1. Check if the user is logged in (auth.php).
 *  2. Verify booking ID and user ownership.
 *  3. Check if the booking is already paid.
 *  4. Check if the booking status is 'booked' (not confirmed, processing, etc.).
 *  5. Update booking status to 'cancelled'.
 *  6. Set the car status back to 'available'.
 *  7. Redirect to my_bookings.php with a session message.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure user is logged in

// --- Retrieve booking ID from GET/POST ---
$booking_id = $_REQUEST['booking_id'] ?? null;
$user_id = $_SESSION['user_id'];

// --- If booking_id or user_id is missing, redirect ---
if (!$booking_id || !$user_id) {
    header("Location: my_bookings.php");
    exit();
}

// --- Check if the booking has already been paid ---
$check = $conn->prepare("SELECT * FROM payments WHERE booking_id = ?");
$check->bind_param("i", $booking_id);
$check->execute();
$payment_exists = $check->get_result()->num_rows > 0;

if ($payment_exists) {
    // Cannot cancel a paid booking
    $_SESSION['success'] = "Đơn này đã thanh toán, không thể hủy."; // "This booking has been paid, cannot cancel."
    header("Location: my_bookings.php");
    exit();
}

// --- Check booking status and ownership ---
$stmt = $conn->prepare("SELECT car_id FROM bookings WHERE booking_id = ? AND user_id = ? AND status = 'booked'");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No booking found or cannot cancel due to status
    $_SESSION['success'] = "Không thể hủy đơn này."; // "Cannot cancel this booking."
    header("Location: my_bookings.php");
    exit();
}

$booking = $result->fetch_assoc();
$car_id = $booking['car_id'];

// --- Update booking status to 'cancelled' ---
$update = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
$update->bind_param("i", $booking_id);
$update->execute();

// --- Set the car status back to 'available' ---
$conn->query("UPDATE cars SET status = 'available' WHERE car_id = $car_id");

// --- Set success message and redirect ---
$_SESSION['success'] = "Đơn đã bị hủy."; // "Booking has been cancelled."
header("Location: my_bookings.php");
exit();
