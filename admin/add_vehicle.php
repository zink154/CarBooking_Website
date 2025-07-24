<?php
/**
 * add_vehicle.php
 *
 * This script allows the admin to add a new vehicle to the system.
 * It includes:
 *  - Validation of vehicle information (car name, type, brand, plate number, etc.).
 *  - Validation of uploaded image (only JPG/PNG accepted).
 *  - Validation that price per km is a multiple of 1000 VND.
 *  - Handling image upload to the server directory (../images/car/).
 *  - Saving the new vehicle data into the database (cars table).
 * 
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection is required (db.php).
 *  - Uses Bootstrap and TomSelect for styling and enhanced UI.
 */

require_once __DIR__ . '/../config/config.php';    // Load configuration settings
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session handling
require_once __DIR__ . '/../config/admin_auth.php';// Ensure only admin can access this page

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form inputs
    $car_name = $_POST['car_name'];
    $car_type = $_POST['car_type'];
    $car_brand = $_POST['car_brand'];
    $plate_number = $_POST['plate_number'];
    $price_per_km = (int)$_POST['price_per_km'];
    $capacity = $_POST['capacity'];
    $status = 'available'; # Default status is 'available'

    // --- Handle image upload ---
    $upload_dir = '../images/car/';
    $filename = basename($_FILES['image_file']['name']);
    $target_file = $upload_dir . $filename;
    $image_url = 'images/car/' . $filename;

    // Validate image type (only JPG/PNG allowed)
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($_FILES['image_file']['type'], $allowed_types)) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Only JPG or PNG images are allowed.</div>";
        exit();
    }

    // Move uploaded file to server folder
    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Cannot upload image to the server.</div>";
        exit();
    }

    // --- Validate price: must be a multiple of 1000 ---
    if ($price_per_km % 1000 !== 0) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Price must be a multiple of 1000 VND.</div>";
    } else {
        // Insert new vehicle record into the cars table
        $stmt = $conn->prepare("INSERT INTO cars (car_name, car_type, car_brand, plate_number, price_per_km, capacity, status, image_url)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdiss", $car_name, $car_type, $car_brand, $plate_number, $price_per_km, $capacity, $status, $image_url);

        // If successful, redirect to vehicles.php
        if ($stmt->execute()) {
            header("Location: vehicles.php");
            exit();
        } else {
            // Display error message if the database insert fails
            echo "<div class='alert alert-danger'>Error adding vehicle: " . $stmt->error . "</div>";
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
    <!-- Bootstrap and TomSelect CSS -->
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
            <!-- Vehicle form with multipart for image upload -->
            <form method="POST" action="" enctype="multipart/form-data" onsubmit="return validatePrice();">
                <!-- Car name -->
                <div class="mb-3">
                    <label class="form-label">Tên xe</label>
                    <input type="text" name="car_name" class="form-control" required>
                </div>

                <!-- Car type -->
                <div class="mb-3">
                    <label class="form-label">Loại xe</label>
                    <select name="car_type" id="car_type" class="form-select" required>
                        <option value="" disabled selected>— Chọn loại xe —</option>
                        <option value="4 chỗ">4 chỗ</option>
                        <option value="7 chỗ">7 chỗ</option>
                        <option value="9 chỗ">9 chỗ</option>
                        <option value="16 chỗ">16 chỗ</option>
                        <option value="24 chỗ">24 chỗ</option>
                        <option value="29 chỗ">29 chỗ</option>
                        <option value="35 chỗ">35 chỗ</option>
                        <option value="45 chỗ">45 chỗ</option>
                    </select>
                </div>

                <!-- Car brand with TomSelect enhancement -->
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
                        <option value="Hino">Hino</option>
                        <option value="Samco">Samco</option>
                        <option value="Thaco">Thaco</option>
                        <option value="Kia Granbird">Kia</option>
                    </select>
                </div>

                <!-- Plate number -->
                <div class="mb-3">
                    <label class="form-label">Biển số</label>
                    <input type="text" name="plate_number" id="plate_number" class="form-control" maxlength="10" required placeholder="VD: 65A-1234">
                </div>

                <!-- Price per km -->
                <div class="mb-3">
                    <label class="form-label">Giá thuê / km (VND):</label>
                    <input type="number" name="price_per_km" class="form-control" step="1000" min="1000" required>
                </div>

                <!-- Capacity (auto-filled based on car type) -->
                <div class="mb-3">
                    <label class="form-label">Số chỗ ngồi (tự động theo loại xe):</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" required readonly>
                </div>

                <!-- Vehicle status -->
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <input type="text" name="status" class="form-control" value="Sẵn sàng" readonly>
                    <input type="hidden" name="status" value="available">
                </div>
                
                <!-- Car image upload -->
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

<!-- JavaScript for dynamic behavior (TomSelect, car capacity, plate number format) -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carTypeSelect = document.getElementById('car_type');
    const capacityInput = document.getElementById('capacity');
    // Automatically set capacity based on selected car type
    carTypeSelect.addEventListener('change', function () {
        switch (this.value) {
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
    });

    // Initialize TomSelect for car brand dropdown
    new TomSelect("#car_brand", {
        create: false,
        placeholder: "Nhập hoặc chọn hiệu xe...",
        highlight: true
    });

    // Validate and format plate number input
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
            alert("The 3rd character must be a letter (A–Z)");
            plateInput.value = digits;
            return;
        }
        if (rest && !/^\d*$/.test(rest)) {
            alert("The part after '-' must contain only numbers");
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

    // Disable placeholder once status is selected
    const statusSelect = document.getElementById('status');
    statusSelect.addEventListener('change', function () {
        const placeholderOption = this.querySelector('option[value=""]');
        if (placeholderOption) {
            placeholderOption.disabled = true;
        }
    });
});

// Client-side validation for price (must be multiple of 1000)
function validatePrice() {
    const priceInput = document.querySelector('input[name="price_per_km"]');
    const price = parseInt(priceInput.value, 10);
    if (price % 1000 !== 0) {
        alert("❌ The rental price per km must be a multiple of 1000 VND.");
        priceInput.focus();
        return false;
    }
    return true;
}
</script>

</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
