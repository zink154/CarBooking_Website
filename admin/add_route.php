<?php
/**
 * add_route.php
 *
 * This script allows the admin to add a new route (departure to arrival) into the system.
 * It includes:
 *  - Validation to ensure departure and arrival locations are not the same.
 *  - Insertion of route data (departure, arrival, distance) into the database.
 *  - Default status for any new route is set to "available".
 *  - Displaying error messages if validation or database insertion fails.
 * 
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection is required (db.php).
 *  - Uses Bootstrap for styling.
 */

require_once __DIR__ . '/../config/config.php';   // General configuration
require_once __DIR__ . '/../config/db.php';       // Database connection
require_once __DIR__ . '/../config/session.php';  // Session management
require_once __DIR__ . '/../config/admin_auth.php'; // Admin authentication check

// Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form inputs and clean unnecessary spaces
    $departure = trim($_POST['departure_location']);
    $arrival = trim($_POST['arrival_location']);
    $distance = floatval($_POST['distance_km']);
    $status = 'available'; // Default status for all new routes

    // Validate that departure and arrival cannot be the same
    if ($departure === $arrival) {
        $error = "❌ Điểm đi và điểm đến không được trùng nhau.";
    } else {
        // Prepare SQL query to insert new route into the 'routes' table
        $stmt = $conn->prepare("INSERT INTO routes (departure_location, arrival_location, distance_km, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $departure, $arrival, $distance, $status);

        // Execute query and check if successful
        if ($stmt->execute()) {
            // Redirect to routes listing page if successful
            header("Location: routes.php");
            exit();
        } else {
            // Show error if insertion failed
            $error = "❌ Lỗi khi thêm tuyến: " . $stmt->error;
        }
    }
}
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm tuyến đường</title>
    <!-- Load Bootstrap and icons for UI styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4 text-dark fw-bold">Thêm tuyến đường mới</h2>

        <!-- Show error message if $error variable is set -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Form for entering new route details -->
        <form action="" method="POST" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label class="form-label">Điểm đi</label>
                <input type="text" name="departure_location" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Điểm đến</label>
                <input type="text" name="arrival_location" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Khoảng cách (km)</label>
                <input type="number" name="distance_km" step="0.1" class="form-control" required>
            </div>

            <!-- No need to select status, it defaults to 'available' -->

            <div class="d-flex justify-content-between">
                <!-- Back button -->
                <a href="routes.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <!-- Submit button to add a new route -->
                <button type="submit" class="btn btn-warning text-dark">
                    <i class="bi bi-plus-circle"></i> Thêm tuyến
                </button>
            </div>
        </form>
    </div>
</body>
</html>

<?php include __DIR__ . '/../views/admin_footer.php'; ?>
