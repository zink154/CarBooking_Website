<?php
// booking_form.php
require_once __DIR__ . '/../config/auth.php';

// Lấy danh sách tuyến đường
$routeQuery = $conn->query("SELECT * FROM routes");

// Lấy danh sách loại xe đang sẵn sàng
$typeQuery = $conn->query("SELECT DISTINCT car_type FROM cars WHERE status = 'available'");

$now = new DateTime();
$nowFormatted = $now->format('Y-m-d\TH:i'); // định dạng cho input[type=datetime-local]
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow rounded-4">
        <div class="card-header bg-yellow text-dark">
            <h3 class="mb-0">Đặt xe mới</h3>
        </div>
        <div class="card-body">
            <form action="booking_result.php" method="POST">
                <div class="mb-3">
                    <label for="route_id" class="form-label">Tuyến đường</label>
                    <select name="route_id" id="route_id" class="form-select" required>
                        <?php while ($row = $routeQuery->fetch_assoc()): ?>
                            <?php
                            if (str_contains($row['departure_location'], 'Ngưng') || str_contains($row['arrival_location'], 'hoạt động')) continue;
                            ?>
                            <option value="<?= $row['route_id'] ?>">
                                <?= $row['departure_location'] ?> → <?= $row['arrival_location'] ?> (<?= $row['distance_km'] ?> km)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <!-- NGÀY NHẬN XE -->
                <div class="mb-3">
                    <label for="pickup_datetime" class="form-label">Ngày nhận xe</label>
                    <input type="datetime-local" name="pickup_datetime" id="pickup_datetime" class="form-control" required value="<?= $nowFormatted ?>">
                    <small class="text-muted">Định dạng: Tháng / Ngày / Năm (MM/DD/YYYY), Sáng - Chiều (AM/PM)</small>
                </div>

                <!-- NGÀY TRẢ XE -->
                <div class="mb-3">
                    <label for="return_datetime" class="form-label">Ngày trả xe</label>
                    <input type="datetime-local" name="return_datetime" id="return_datetime" class="form-control" required>
                    <small class="text-muted">Định dạng: Tháng / Ngày / Năm (MM/DD/YYYY), Sáng - Chiều (AM/PM) </small>
                </div>

                <div class="mb-3">
                    <label for="car_type" class="form-label">Loại xe</label>
                    <select name="car_type" id="car_type" class="form-select" required>
                        <?php while ($type = $typeQuery->fetch_assoc()): ?>
                            <option value="<?= $type['car_type'] ?>"><?= $type['car_type'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-yellow w-100">Tìm xe phù hợp</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
