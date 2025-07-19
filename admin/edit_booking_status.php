<?php
/**
 * edit_booking_status.php
 *
 * This script updates the status of a booking and adjusts the car status accordingly.
 * It is designed to work via an AJAX request from bookings.php.
 *
 * Features:
 *  - Validates the booking ID and the new status.
 *  - Updates the booking status in the database.
 *  - Automatically updates the car's status based on the booking status:
 *      - If booking is completed/cancelled: car becomes 'available'.
 *      - If booking is confirmed/processing: car becomes 'in_use'.
 *  - Returns a JSON response indicating success or failure.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session handling
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication

// --- Check if the request is POST and required parameters are provided ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['new_status'])) {
    $booking_id = intval($_POST['booking_id']);   // Booking ID to update
    $new_status = $_POST['new_status'];           // New status value

    // --- List of valid booking statuses ---
    $valid_statuses = ['booked', 'confirmed', 'processing', 'completed', 'cancelled'];

    // Validate the provided status
    if (!in_array($new_status, $valid_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ.']); // "Invalid status"
        exit;
    }

    // --- Update the booking status in the database ---
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
    $stmt->bind_param("si", $new_status, $booking_id);

    if ($stmt->execute()) {
        /**
         * --- Car status synchronization ---
         * Depending on the booking status, we adjust the car's availability:
         *  - 'completed' or 'cancelled' → Car is available.
         *  - 'processing' or 'confirmed' → Car is in use.
         */

        if (in_array($new_status, ['completed', 'cancelled'])) {
            // Update car to 'available'
            $update_car = $conn->prepare("
                UPDATE cars 
                SET status = 'available' 
                WHERE car_id = (SELECT car_id FROM bookings WHERE booking_id = ?)
            ");
            $update_car->bind_param("i", $booking_id);
            $update_car->execute();
            $update_car->close();
        }
        elseif (in_array($new_status, ['processing', 'confirmed'])) {
            // Update car to 'in_use'
            $update_car = $conn->prepare("
                UPDATE cars 
                SET status = 'in_use' 
                WHERE car_id = (SELECT car_id FROM bookings WHERE booking_id = ?)
            ");
            $update_car->bind_param("i", $booking_id);
            $update_car->execute();
            $update_car->close();
        }

        // Success response
        echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']); // "Status updated successfully"
    } else {
        // Error response if update fails
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái.']); // "Error updating status"
    }

    $stmt->close();
} else {
    // Invalid request (missing POST data or incorrect method)
    echo json_encode(['success' => false, 'message' => 'Dữ liệu gửi không hợp lệ.']); // "Invalid request data"
}
?>
