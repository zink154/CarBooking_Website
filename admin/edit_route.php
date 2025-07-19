<?php 
/**
 * edit_route.php
 *
 * This script allows the admin to edit an existing route.
 * It provides:
 *  - Validation to ensure departure and arrival locations are not the same.
 *  - Update of route information (departure, arrival, distance, and status).
 *  - Form pre-filled with current route data.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session handling
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication

// Determine the "back" URL (previous page or default routes.php)
$back_url = $_SERVER['HTTP_REFERER'] ?? 'routes.php';

// Check if route ID is provided
if (!isset($_GET['id'])) {
    die("Thiếu ID tuyến đường."); // "Missing route ID."
}

$route_id = intval($_GET['id']); // Ensure route_id is integer

// Variable to hold error messages
$error = '';

// --- Handle form submission ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $departure = trim($_POST['departure_location']);
    $arrival = trim($_POST['arrival_location']);
    $distance = floatval($_POST['distance_km']);
    $status = $_POST['status'];

    // Validate that departure and arrival cannot be the same
    if ($departure === $arrival) {
        $error = "❌ Điểm đi và điểm đến không được trùng nhau."; // "Departure and arrival cannot be the same."
    } else {
        // Prepare SQL to update route details
        $stmt = $conn->prepare("
            UPDATE routes 
            SET departure_location=?, arrival_location=?, distance_km=?, status=? 
            WHERE route_id=?
        ");
        $stmt->bind_param("ssdsi", $departure, $arrival, $distance, $status, $route_id);

        if ($stmt->execute()) {
            // Redirect to routes.php if update is successful
            header("Location: routes.php");
            exit();
        } else {
            $error = "❌ Lỗi khi cập nhật: " . $stmt->error; // "Error updating route"
        }
    }
}

// --- Fetch current route information ---
$stmt = $conn->prepare("SELECT * FROM routes WHERE route_id = ?");
$stmt->bind_param("i", $route_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy tuyến."); // "Route not found."
}

$route = $result->fetch_assoc();

// Clean up displayed names (remove extra text in parentheses if any)
$departure_clean = preg_replace('/\(.*?\)/', '', $route['departure_location']);
$arrival_clean = preg_replace('/\(.*?\)/', '', $route['arrival_location']);
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa tuyến đường</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h3 class="card-title mb-4 text-center text-primary">
                    Sửa tuyến: <?= htmlspecialchars($departure_clean) ?> → <?= htmlspecialchars($arrival_clean) ?>
                </h3>

                <!-- Display error message if exists -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <!-- Edit route form -->
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Điểm đi:</label>
                        <input type="text" name="departure_location" class="form-control"
                               value="<?= htmlspecialchars(trim($departure_clean)) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Điểm đến:</label>
                        <input type="text" name="arrival_location" class="form-control"
                               value="<?= htmlspecialchars(trim($arrival_clean)) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Khoảng cách (km):</label>
                        <input type="number" step="0.1" name="distance_km" class="form-control"
                               value="<?= $route['distance_km'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái tuyến</label>
                        <select name="status" id="status" class="form-select">
                            <option value="available" <?= $route['status'] === 'available' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="unavailable" <?= $route['status'] === 'unavailable' ? 'selected' : '' ?>>Ngưng hoạt động</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= htmlspecialchars($back_url) ?>" class="btn btn-outline-secondary">← Quay lại</a>
                        <button type="submit" class="btn btn-primary">💾 Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
