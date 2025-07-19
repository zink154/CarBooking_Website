<?php
// booking_result.php

/**
 * This page displays the list of available cars that match
 * the user's booking request (route, car type, pickup/return times).
 * 
 * Enhancements:
 *  - Validate that pickup_datetime < return_datetime before querying cars.
 *  - Show an error message (Bootstrap alert) if the date range is invalid.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure user is authenticated

$error_message = '';
$cars = null;
$route = null;
$distance_km = 0;
$pickup = null;
$return = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $route_id = $_POST['route_id'];
    $car_type = $_POST['car_type'];
    $pickup_datetime = $_POST['pickup_datetime'];
    $return_datetime = $_POST['return_datetime'];

    // --- Validate pickup and return dates ---
    if (strtotime($pickup_datetime) >= strtotime($return_datetime)) {
        $error_message = "❌ Ngày nhận xe phải nhỏ hơn ngày trả xe."; // Show error
    } else {
        // --- Fetch route details ---
        $routeStmt = $conn->prepare("SELECT * FROM routes WHERE route_id = ?");
        $routeStmt->bind_param("i", $route_id);
        $routeStmt->execute();
        $routeResult = $routeStmt->get_result();
        $route = $routeResult->fetch_assoc();

        if (!$route) {
            $error_message = "❌ Không tìm thấy tuyến đường.";
        } else {
            $distance_km = $route['distance_km'];

            // --- Fetch cars matching car type and availability ---
            $carStmt = $conn->prepare("SELECT * FROM cars WHERE car_type = ? AND status = 'available'");
            $carStmt->bind_param("s", $car_type);
            $carStmt->execute();
            $cars = $carStmt->get_result();
        }
    }

    // --- Create DateTime objects for display ---
    $pickup = DateTime::createFromFormat('Y-m-d\TH:i', $pickup_datetime);
    $return = DateTime::createFromFormat('Y-m-d\TH:i', $return_datetime);
} else {
    // Redirect back if request is not POST
    header("Location: booking_form.php");
    exit();
}
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Danh sách xe phù hợp</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($error_message)): ?>
                <!-- Display error message -->
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
                <a href="booking_form.php" class="btn btn-secondary mt-3">← Quay lại form đặt xe</a>
            <?php else: ?>
                <p><strong>Tuyến:</strong> <?= $route['departure_location'] ?> → <?= $route['arrival_location'] ?> (<?= $distance_km ?> km)</p>
                <p>
                    <strong>Loại xe:</strong> <?= htmlspecialchars($car_type) ?> &nbsp; | &nbsp;
                    <strong>Nhận xe:</strong> <?= $pickup ? $pickup->format('d/m/Y H:i') : $pickup_datetime ?> &nbsp; | &nbsp;
                    <strong>Trả xe:</strong> <?= $return ? $return->format('d/m/Y H:i') : $return_datetime ?>
                </p>

                <?php if ($cars && $cars->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Biển số</th>
                                    <th>Hiệu xe</th>
                                    <th>Số chỗ</th>
                                    <th>Giá/km</th>
                                    <th>Tổng giá</th>
                                    <th>Chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($car = $cars->fetch_assoc()):
                                    $total_price = $car['price_per_km'] * $distance_km;
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?= rtrim(BASE_URL, '/') . '/' . ltrim($car['image_url'], '/') ?>" 
                                                 alt="Hình xe" width="200" class="img-thumbnail">
                                        </td>
                                        <td><?= $car['plate_number'] ?></td>
                                        <td><?= $car['car_brand'] ?></td>
                                        <td><span class="badge bg-secondary"><?= $car['capacity'] ?> chỗ</span></td>
                                        <td><?= number_format($car['price_per_km'], 0) ?> <small class="text-muted">VND/km</small></td>
                                        <td><strong class="text-success"><?= number_format($total_price, 0) ?> VND</strong></td>
                                        <td>
                                            <form action="booking_process.php" method="POST">
                                                <input type="hidden" name="route_id" value="<?= $route_id ?>">
                                                <input type="hidden" name="car_id" value="<?= $car['car_id'] ?>">
                                                <input type="hidden" name="pickup_datetime" value="<?= $pickup_datetime ?>">
                                                <input type="hidden" name="return_datetime" value="<?= $return_datetime ?>">
                                                <input type="hidden" name="total_price" value="<?= $total_price ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Đặt xe</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <p class="text-muted mt-2 fst-italic">
                            * Giá trên chỉ mang tính tham khảo. Vui lòng liên hệ với chúng tôi để biết thông tin chính xác và đầy đủ.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-4">Không tìm thấy xe phù hợp với lựa chọn của bạn.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
