<?php
/**
 * delete_route.php
 *
 * This script marks a specific route as "inactive" by updating its name, 
 * instead of deleting it from the database.
 * 
 * Steps:
 *  1. Check if route ID is provided via GET.
 *  2. Fetch the route information from the database.
 *  3. If the route exists, append labels "(Ngưng)" and "(hoạt động)" to departure/arrival names.
 *  4. Update the route record in the database.
 *  5. Redirect back to the routes listing page (routes.php).
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
    die("Thiếu ID tuyến."); // "Missing route ID."
}

$route_id = intval($_GET['id']); // Convert to integer for safety

// --- Fetch current route ---
$stmt = $conn->prepare("SELECT * FROM routes WHERE route_id = ?");
$stmt->bind_param("i", $route_id);
$stmt->execute();
$route = $stmt->get_result()->fetch_assoc();

if (!$route) {
    die("Không tìm thấy tuyến."); // "Route not found."
}

// --- Mark route as inactive by changing its names ---
$new_departure = $route['departure_location'] . " (Ngưng)";
$new_arrival = $route['arrival_location'] . " (hoạt động)"; // Possibly should be "(Ngưng)" for consistency

// --- Update the route in the database ---
$update = $conn->prepare("UPDATE routes SET departure_location = ?, arrival_location = ? WHERE route_id = ?");
$update->bind_param("ssi", $new_departure, $new_arrival, $route_id);

// --- Execute and handle result ---
if ($update->execute()) {
    header("Location: routes.php"); // Redirect to route list
    exit();
} else {
    echo "Không thể cập nhật tuyến: " . $update->error; // "Unable to update route"
}
?>
