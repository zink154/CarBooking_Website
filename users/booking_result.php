<?php
// booking_result.php

require_once __DIR__ . '/../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $route_id = $_POST['route_id'];
    $car_type = $_POST['car_type'];
    $pickup_datetime = $_POST['pickup_datetime'];
    $return_datetime = $_POST['return_datetime'];

    // Lấy thông tin tuyến đường
    $routeStmt = $conn->prepare("SELECT * FROM routes WHERE route_id = ?");
    $routeStmt->bind_param("i", $route_id);
    $routeStmt->execute();
    $routeResult = $routeStmt->get_result();
    $route = $routeResult->fetch_assoc();

    if (!$route) {
        echo "Không tìm thấy tuyến đường.";
        exit();
    }

    $distance_km = $route['distance_km'];

    // Lấy danh sách xe phù hợp
    $carStmt = $conn->prepare("SELECT * FROM cars WHERE car_type = ? AND status = 'available'");
    $carStmt->bind_param("s", $car_type);
    $carStmt->execute();
    $cars = $carStmt->get_result();
} else {
    header("Location: booking_form.php");
    exit();
}

$pickup = DateTime::createFromFormat('Y-m-d\TH:i', $pickup_datetime);
$return = DateTime::createFromFormat('Y-m-d\TH:i', $return_datetime);

?>
<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Danh sách xe phù hợp</h3>
        </div>
        <div class="card-body">
            <p><strong>Tuyến:</strong> <?= $route['departure_location'] ?> → <?= $route['arrival_location'] ?> (<?= $distance_km ?> km)</p>
            <p>
                <strong>Loại xe:</strong> <?= htmlspecialchars($car_type) ?> &nbsp; | &nbsp;
                <strong>Nhận xe:</strong> <?= $pickup ? $pickup->format('d/m/Y H:i') : $pickup_datetime ?> &nbsp; | &nbsp;
                <strong>Trả xe:</strong> <?= $return ? $return->format('d/m/Y H:i') : $return_datetime ?>
            </p>

            <?php if ($cars->num_rows > 0): ?>
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
                                        <img src="<?= rtrim(BASE_URL, '/') . '/' . ltrim($car['image_url'], '/') ?>" alt="Hình xe" width="200" class="img-thumbnail">
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
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
