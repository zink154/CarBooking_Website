<?php
/**
 * delete_route.php
 *
 * This script marks a specific route as "inactive" by updating its status 
 * instead of deleting it from the database.
 *
 * Steps:
 *  1. Check if route ID is provided via GET.
 *  2. Update the route's status = 'unavailable'.
 *  3. Redirect back to routes listing page.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session management
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication

// --- Validate route ID ---
if (!isset($_GET['id'])) {
    die("Thiếu ID tuyến."); // Missing route ID
}

$route_id = intval($_GET['id']); // Convert to integer for safety

// --- Update the route's status to unavailable ---
$stmt = $conn->prepare("UPDATE routes SET status = 'unavailable' WHERE route_id = ?");
$stmt->bind_param("i", $route_id);

if ($stmt->execute()) {
    header("Location: routes.php"); // Redirect to route list
    exit();
} else {
    echo "Không thể cập nhật trạng thái tuyến: " . $stmt->error; // Unable to update route status
}
