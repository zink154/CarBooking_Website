<?php 

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID xe.");
}

$car_id = $_GET['id'];

// Lấy thông tin xe hiện tại
$stmt = $conn->prepare("SELECT * FROM cars WHERE car_id = ?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy xe.");
}
$car = $result->fetch_assoc();

// Xử lý khi gửi form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_type = $_POST['car_type'];
    $car_brand = $_POST['car_brand'];
    $plate_number = $_POST['plate_number'];
    $price_per_km = (int)$_POST['price_per_km'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    $image_url = $car['image_url']; // giữ mặc định là ảnh cũ

    // Nếu có upload ảnh mới
    if (!empty($_FILES['image_file']['name'])) {
        $upload_dir = '../images/car/';
        $filename = basename($_FILES['image_file']['name']);
        $target_file = $upload_dir . $filename;
        $allowed_types = ['image/jpeg', 'image/png'];

        if (!in_array($_FILES['image_file']['type'], $allowed_types)) {
            echo "<div class='alert alert-danger text-center mt-3'>❌ Chỉ chấp nhận ảnh JPG hoặc PNG.</div>";
            exit();
        }

        if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            echo "<div class='alert alert-danger text-center mt-3'>❌ Không thể tải ảnh lên máy chủ.</div>";
            exit();
        }

        $image_url = 'images/car/' . $filename;
    }

    if ($price_per_km % 1000 !== 0) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Giá thuê phải là bội số của 1000 VND.</div>";
    } else {
        $stmt = $conn->prepare("UPDATE cars SET car_type=?, car_brand=?, plate_number=?, price_per_km=?, capacity=?, status=?, image_url=? WHERE car_id=?");
        $stmt->bind_param("sssdissi", $car_type, $car_brand, $plate_number, $price_per_km, $capacity, $status, $image_url, $car_id);

        if ($stmt->execute()) {
            header("Location: vehicles.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi cập nhật: " . $stmt->error . "</div>";
        }
    }
}
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Sửa thông tin xe: <?= htmlspecialchars($car['car_name']) ?> (<?= htmlspecialchars($car['plate_number']) ?>)</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" onsubmit="return validatePrice();">
                <div class="mb-3">
                    <label class="form-label">Loại xe</label>
                    <select name="car_type" id="car_type" class="form-select" required>
                        <?php
                        $types = ['4 chỗ', '7 chỗ', '9 chỗ', '16 chỗ', '24 chỗ', '29 chỗ', '35 chỗ', '45 chỗ'];
                        foreach ($types as $type) {
                            $selected = ($car['car_type'] === $type) ? 'selected' : '';
                            echo "<option value=\"$type\" $selected>$type</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hiệu xe</label>
                    <input type="text" name="car_brand" class="form-control" value="<?= htmlspecialchars($car['car_brand']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Biển số</label>
                    <input type="text" name="plate_number" class="form-control" value="<?= htmlspecialchars($car['plate_number']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá thuê / km (VND)</label>
                    <input type="number" name="price_per_km" class="form-control" step="1000" min="1000" value="<?= htmlspecialchars($car['price_per_km']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số chỗ ngồi (tự động theo loại xe):</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" value="<?= htmlspecialchars($car['capacity']) ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select" required>
                        <option value="available" <?= $car['status'] === 'available' ? 'selected' : '' ?>>Sẵn sàng</option>
                        <option value="in_use" <?= $car['status'] === 'in_use' ? 'selected' : '' ?>>Đang sử dụng</option>
                        <option value="maintenance" <?= $car['status'] === 'maintenance' ? 'selected' : '' ?>>Bảo trì</option>
                        <option value="unavailable" <?= $car['status'] === 'unavailable' ? 'selected' : '' ?>>Ngưng hoạt động</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh hiện tại</label><br>
                    <?php if (!empty($car['image_url'])): ?>
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($car['image_url']) ?>" alt="Ảnh xe" style="width: 120px; border-radius: 8px;">
                    <?php else: ?>
                        <span class="text-muted">Không có ảnh</span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tải ảnh mới (nếu muốn thay thế)</label>
                    <input type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="vehicles.php" class="btn btn-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carTypeSelect = document.getElementById('car_type');
    const capacityInput = document.getElementById('capacity');
    const priceInput = document.querySelector('input[name="price_per_km"]');

    // Hàm cập nhật số chỗ ngồi dựa vào loại xe
    function updateCapacity() {
        switch (carTypeSelect.value) {
            case '4 chỗ': capacityInput.value = 4; break;
            case '7 chỗ': capacityInput.value = 7; break;
            case '9 chỗ': capacityInput.value = 9; break;
            case '16 chỗ': capacityInput.value = 16; break;
            case '24 chỗ': capacityInput.value = 24; break;
            case '29 chỗ': capacityInput.value = 29; break;
            case '35 chỗ': capacityInput.value = 35; break;
            case '45 chỗ': capacityInput.value = 45; break;
            default: capacityInput.value = '';
        }
    }

    // Gắn sự kiện thay đổi loại xe
    carTypeSelect.addEventListener('change', updateCapacity);
    updateCapacity(); // cập nhật khi tải trang

    // Gắn sự kiện validate giá thuê trước khi submit form
    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        const price = parseInt(priceInput.value, 10);
        if (price % 1000 !== 0) {
            alert("❌ Giá thuê mỗi km phải là bội số của 1000 VND.");
            priceInput.focus();
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
