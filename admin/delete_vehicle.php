<?php
/**
 * delete_route.php
 *
 * This script marks a specific route as 'unavailable' instead of renaming 
 * or deleting it from the database. The route data remains intact but 
 * becomes inactive for future bookings.
 *
 * Steps:
 *  1. Verify that a route ID is passed via GET.
 *  2. Fetch the route from the database to ensure it exists.
 *  3. Update the 'status' field of the route to 'unavailable'.
 *  4. Redirect back to routes.php on success or show an error message if failed.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session handling
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication

// --- Check if route ID is provided ---
if (!isset($_GET['id'])) {
    die("Thiếu ID tuyến."); // "Route ID is missing."
}

$route_id = intval($_GET['id']); // Convert to integer for safety

// --- Check if the route exists ---
$stmt = $conn->prepare("SELECT route_id FROM routes WHERE route_id = ?");
$stmt->bind_param("i", $route_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Không tìm thấy tuyến."); // "Route not found."
}

// --- Update route status to 'unavailable' ---
$update = $conn->prepare("UPDATE routes SET status = 'unavailable' WHERE route_id = ?");
$update->bind_param("i", $route_id);

// --- Execute update and handle result ---
if ($update->execute()) {
    // Redirect to routes listing page
    header("Location: routes.php");
    exit();
} else {
    // Show error message if update fails
    echo "Không thể cập nhật trạng thái tuyến: " . $update->error;
}
?>
