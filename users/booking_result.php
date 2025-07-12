<?php
require_once 'config/session.php';
require_once 'config/auth.php';
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm xe</title>
</head>
<body>
    <h2>Danh sách xe phù hợp</h2>
    <p>Tuyến: <?= $route['departure_location'] ?> → <?= $route['arrival_location'] ?> (<?= $distance_km ?> km)</p>
    <p>Loại xe: <?= $car_type ?> | Nhận xe: <?= $pickup_datetime ?> | Trả xe: <?= $return_datetime ?></p>

    <?php if ($cars->num_rows > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Hình ảnh</th>
                <th>Biển số</th>
                <th>Hiệu xe</th>
                <th>Số chỗ</th>
                <th>Giá/km</th>
                <th>Tổng giá</th>
                <th>Chọn</th>
            </tr>
            <?php while ($car = $cars->fetch_assoc()): 
                $total_price = $car['price_per_km'] * $distance_km;
            ?>
                <tr>
                    <td><img src="<?= $car['image_url'] ?>" alt="Hình xe" width="100"></td>
                    <td><?= $car['plate_number'] ?></td>
                    <td><?= $car['car_brand'] ?></td>
                    <td><?= $car['capacity'] ?> chỗ</td>
                    <td><?= number_format($car['price_per_km'], 0) ?> VND/km</td>
                    <td><?= number_format($total_price, 0) ?> VND</td>
                    <td>
                        <form action="booking_process.php" method="POST">
                            <input type="hidden" name="route_id" value="<?= $route_id ?>">
                            <input type="hidden" name="car_id" value="<?= $car['car_id'] ?>">
                            <input type="hidden" name="pickup_datetime" value="<?= $pickup_datetime ?>">
                            <input type="hidden" name="return_datetime" value="<?= $return_datetime ?>">
                            <input type="hidden" name="total_price" value="<?= $total_price ?>">
                            <button type="submit">Đặt xe</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Không tìm thấy xe phù hợp.</p>
    <?php endif; ?>
</body>
</html>
