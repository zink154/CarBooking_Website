<?php
require_once 'config/session.php';
require_once 'config/auth.php'; // Kiểm tra đăng nhập
require_once 'config/db.php';   // Biến $conn đã được tạo

// Lấy danh sách tuyến đường
$routeQuery = $conn->query("SELECT * FROM routes");

// Lấy danh sách loại xe đang sẵn sàng
$typeQuery = $conn->query("SELECT DISTINCT car_type FROM cars WHERE status = 'available'");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt xe</title>
</head>
<body>
    <h2>Đặt xe mới</h2>

    <form action="booking_result.php" method="POST">
        <label for="route_id">Tuyến đường:</label><br>
        
        <select name="route_id" required>
            <?php while ($row = $routes->fetch_assoc()): ?>
                <?php
                // Ẩn tuyến có tên chứa "(Ngưng" hoặc "(hoạt động)"
                if (str_contains($row['departure_location'], 'Ngưng') || str_contains($row['arrival_location'], 'hoạt động')) continue;
                ?>
                <option value="<?= $row['route_id'] ?>">
                    <?= $row['departure_location'] ?> → <?= $row['arrival_location'] ?> (<?= $row['distance_km'] ?> km)
                </option>
            <?php endwhile; ?>
        </select>

        <label for="pickup_datetime">Ngày nhận xe:</label><br>
        <input type="datetime-local" name="pickup_datetime" id="pickup_datetime" required><br><br>

        <label for="return_datetime">Ngày trả xe:</label><br>
        <input type="datetime-local" name="return_datetime" id="return_datetime" required><br><br>

        <label for="car_type">Loại xe:</label><br>
        <select name="car_type" id="car_type" required>
            <?php while ($type = $typeQuery->fetch_assoc()): ?>
                <option value="<?= $type['car_type'] ?>"><?= $type['car_type'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Tìm xe phù hợp</button>
    </form>
</body>
</html>
