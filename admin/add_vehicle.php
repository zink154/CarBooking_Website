<?php
require_once '../config/autoload_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_name = $_POST['car_name'];
    $car_type = $_POST['car_type'];
    $car_brand = $_POST['car_brand'];
    $plate_number = $_POST['plate_number'];
    $price_per_km = (int)$_POST['price_per_km'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    // Xử lý upload ảnh
    $upload_dir = '../images/car/';
    $filename = basename($_FILES['image_file']['name']);
    $target_file = $upload_dir . $filename;
    $image_url = 'images/car/' . $filename;

    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($_FILES['image_file']['type'], $allowed_types)) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Chỉ chấp nhận ảnh JPG hoặc PNG.</div>";
        exit();
    }

    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Không thể tải ảnh lên máy chủ.</div>";
        exit();
    }

    // Kiểm tra phía server: giá thuê phải chia hết cho 1000
    if ($price_per_km % 1000 !== 0) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Giá thuê phải là bội số của 1000 VND.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO cars (car_name, car_type, car_brand, plate_number, price_per_km, capacity, status, image_url)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdiss", $car_name, $car_type, $car_brand, $plate_number, $price_per_km, $capacity, $status, $image_url);

        if ($stmt->execute()) {
            header("Location: vehicles.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi thêm xe: " . $stmt->error . "</div>";
        }
    }
}
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm xe mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-yellow text-dark">
            <h4 class="mb-0">Thêm xe mới</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data" onsubmit="return validatePrice();">
                <div class="mb-3">
                    <label class="form-label">Tên xe</label>
                    <input type="text" name="car_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Loại xe</label>
                    <select name="car_type" id="car_type" class="form-select" required>
                        <option value="" disabled selected>— Chọn loại xe —</option>
                        <option value="4 chỗ">4 chỗ</option>
                        <option value="7 chỗ">7 chỗ</option>
                        <option value="16 chỗ">16 chỗ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hiệu xe</label>
                    <select id="car_brand" name="car_brand" class="form-select" required>
                        <option value="">— Chọn hiệu xe —</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Kia">Kia</option>
                        <option value="Ford">Ford</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Mitsubishi">Mitsubishi</option>
                        <option value="Honda">Honda</option>
                        <option value="Mazda">Mazda</option>
                        <option value="Isuzu">Isuzu</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Chevrolet">Chevrolet</option>
                        <option value="VinFast">VinFast</option>
                        <option value="Mercedes">Mercedes</option>
                        <option value="BMW">BMW</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Biển số</label>
                    <input type="text" name="plate_number" id="plate_number" class="form-control" maxlength="10" required placeholder="VD: 65A-1234">
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá thuê / km (VND):</label>
                    <input type="number" name="price_per_km" class="form-control" step="1000" min="1000" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số chỗ ngồi (tự động theo loại xe):</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" required readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="" selected disabled>– Chọn trạng thái –</option>
                        <option value="available">Sẵn sàng</option>
                        <option value="in_use">Đang sử dụng</option>
                        <option value="maintenance">Bảo trì</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh xe</label>
                    <input type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="vehicles.php" class="btn btn-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-success">Lưu xe</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carTypeSelect = document.getElementById('car_type');
    const capacityInput = document.getElementById('capacity');
    carTypeSelect.addEventListener('change', function () {
        switch (this.value) {
            case '4 chỗ': capacityInput.value = 4; break;
            case '7 chỗ': capacityInput.value = 7; break;
            case '16 chỗ': capacityInput.value = 16; break;
            default: capacityInput.value = '';
        }
    });

    new TomSelect("#car_brand", {
        create: false,
        placeholder: "Nhập hoặc chọn hiệu xe...",
        maxOptions: 10,
        highlight: true
    });

    const plateInput = document.getElementById('plate_number');
    plateInput.addEventListener('input', function () {
        let value = plateInput.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
        if (value.length > 8) value = value.slice(0, 8);
        const digits = value.substring(0, 2);
        const letter = value.charAt(2);
        const rest = value.substring(3);

        if (!/^\d{0,2}$/.test(digits)) {
            plateInput.value = digits.replace(/\D/g, '');
            return;
        }
        if (letter && !/^[A-Z]$/.test(letter)) {
            alert("Ký tự thứ 3 phải là chữ cái (A–Z)");
            plateInput.value = digits;
            return;
        }
        if (rest && !/^\d*$/.test(rest)) {
            alert("Phần sau dấu '-' chỉ được nhập số");
            plateInput.value = digits + letter + (rest ? '-' + rest.replace(/\D/g, '') : '');
            return;
        }

        let formatted = '';
        if (digits && letter) {
            formatted = digits + letter + '-';
        } else {
            formatted = digits + (letter || '');
        }
        if (rest) formatted += rest;
        plateInput.value = formatted;
    });

    const statusSelect = document.getElementById('status');
    statusSelect.addEventListener('change', function () {
        const placeholderOption = this.querySelector('option[value=""]');
        if (placeholderOption) {
            placeholderOption.disabled = true;
        }
    });
});

function validatePrice() {
    const priceInput = document.querySelector('input[name="price_per_km"]');
    const price = parseInt(priceInput.value, 10);
    if (price % 1000 !== 0) {
        alert("❌ Giá thuê mỗi km phải là bội số của 1000 VND.");
        priceInput.focus();
        return false;
    }
    return true;
}
</script>

</body>
</html>
<?php include __DIR__ . '/../views/footer.php'; ?>