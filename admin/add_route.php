<?php
require_once '../config/autoload_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $departure = trim($_POST['departure_location']);
    $arrival = trim($_POST['arrival_location']);
    $distance = floatval($_POST['distance_km']);

    if ($departure === $arrival) {
        $error = "❌ Điểm đi và điểm đến không được trùng nhau.";
    } else {
        $stmt = $conn->prepare("INSERT INTO routes (departure_location, arrival_location, distance_km) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $departure, $arrival, $distance);

        if ($stmt->execute()) {
            header("Location: routes.php");
            exit();
        } else {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4 text-dark fw-bold">Thêm tuyến đường mới</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

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

           <div class="d-flex justify-content-between">
            <a href="routes.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-warning text-dark">
                <i class="bi bi-plus-circle"></i> Thêm tuyến
            </button>
            </div>

        </form>
    </div>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
