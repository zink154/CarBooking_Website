<?php

// Kiểm tra và khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra trạng thái đăng nhập
$is_logged_in = isset($_SESSION['user']); // Kiểm tra xem người dùng đã đăng nhập chưa
$user_name = $is_logged_in ? $_SESSION['user']['name'] : ''; // Tên người dùng, lưu trong session
$user_role = $is_logged_in ? $_SESSION['user']['type'] : ''; // Vai trò người dùng, lưu trong session
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TamHang Tourist</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
        <style>
        /* Màu nền navbar */
        .navbar-custom {
            background-color: #fcb213 !important;
        }

        /* In đậm toàn bộ chữ navbar */
        .navbar .nav-link {
            color: #000 !important; /* Đặt màu chữ thành đen */
            font-weight: 700;
            transition: all 0.2s ease;
            text-align: center;
            display: block;
        }

        /* Hiển thị dropdown khi hover */
        .navbar-nav .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            visibility: visible;
        }

        .dropdown-menu {
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .navbar-nav .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
        }
        .navbar .nav-link {
        font-weight: 700;
        transition: all 0.2s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
            border-radius: 5px;
            padding: 6px 12px;
        }

        .fixed-buttons {
            position: fixed;
            bottom: 150px;
            right: 15px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .fixed-buttons a {
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; /* Để dùng ::after */
        }

        .fixed-buttons img.fixed-icon {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        /* Tooltip khi hover vào nút Messenger */
        .fixed-buttons a:hover::after {
            content: attr(data-tooltip); /* Lấy nội dung từ thuộc tính data-tooltip */
            position: absolute;
            right: 130%; /* Hiển thị bên trái icon */
            top: 50%;
            transform: translateY(-50%);
            background-color: #fcb213;
            color: black;
            padding: 6px 10px;
            border-radius: 5px;
            white-space: nowrap;
            font-size: 14px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        
        .btn-yellow {
            background-color: #fcb213 !important;
            color: black !important;
            font-weight: 600;
            border: none;
        }

        .bg-yellow {
            background-color: #fcb213 !important;
            color: black !important;
            font-weight: 600;
            border: none;
        }

        .btn-yellow:hover {
            background-color: #e5a500 !important;
            color: black !important;
        }

        .contact-button {
            padding: 12px 24px;
            font-size: 18px;
            border: 2px solid #fcb213;
            border-radius: 8px;
            color: #fcb213;
            background-color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: glowPulse 1.5s infinite;
        }

        .contact-button:hover {
            background-color: #fcb213;
            color: white;
            border-color: #e2a100;
        }

        @keyframes glowPulse {
            0% {
                box-shadow: 0 0 10px rgba(252, 178, 19, 0.3);
            }
            50% {
                box-shadow: 0 0 20px rgba(252, 178, 19, 0.8);
            }
            100% {
                box-shadow: 0 0 10px rgba(252, 178, 19, 0.3);
            }
        }
    </style>

</head>
<body>

    <!-- Nút cố định bên trái -->
    <div class="fixed-buttons">

        <!-- Nút Messenger với tooltip -->
        <a href="https://www.facebook.com/profile.php?id=61561758564355" target="_blank" data-tooltip="Messenger">
            <img src="<?= BASE_URL ?>/images/assets/icons/Messenger.png" alt="Messenger" class="fixed-icon">
        </a>

        <!-- Nút Tiktok với tooltip -->
        <a href="https://www.tiktok.com/@tm.hng.tourist" target="_blank" data-tooltip="Tiktok">
            <img src="<?= BASE_URL ?>/images/assets/icons/tiktok.png" alt="Messenger" class="fixed-icon">
        </a>

        <!-- Nút Google Maps -->
        <a href="https://www.google.com/maps?ll=9.987448,105.745761&z=18&t=m&hl=en&gl=US&mapclient=embed&q=9°59%2715.1%22N+105°44%2746.7%22E+9.987528,+105.746306@9.987527799999999,105.7463056" 
        target="_blank" 
        data-tooltip="Google Maps">
            <img src="<?= BASE_URL ?>/images/assets/icons/map.png" alt="Google Maps" class="fixed-icon">
        </a>
        
    </div>

    <header class="main-header">
        <!-- Thanh điều hướng -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
                    <img src="<?= BASE_URL ?>/images/index/Logo_2.png" alt="Logo" height="40">
                    <span>TamHang Tourist</span>
                </a>

                
                <!-- Nút Toggle cho màn hình nhỏ -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu điều hướng -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto text-center">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">TRANG CHỦ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/intro.php">GIỚI THIỆU</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="<?= BASE_URL ?>/car_categories.php" id="carDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                DÒNG XE
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="carDropdown">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/car_4.php">Xe 4 chỗ</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/car_7.php">Xe 7 chỗ</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/car_16.php">Xe 16 chỗ</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/users/booking_form.php">ĐẶT XE</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/users/book_car.php">DỊCH VỤ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/contact.php">LIÊN HỆ</a></li>

                        <!-- Hiển thị thông tin người dùng -->
                        <?php if ($is_logged_in): ?>
                            <li class="nav-item dropdown position-relative">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" aria-expanded="false">
                                    Xin chào, <?= htmlspecialchars($user_name); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/profile.php">Hồ sơ của tôi</a></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>change_password.php">Đổi mật khẩu</a></li> <!-- Đổi mật khẩu -->
                                    
                                    <!-- Hiển thị Dashboard nếu là admin -->
                                    <?php if ($user_role === 'admin'): ?>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/dashboard.php">Truy cập Dashboard</a></li>
                                    <?php endif; ?>
                                    
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout.php">Đăng xuất</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/login.php">ĐĂNG NHẬP</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("carDropdown").addEventListener("click", function (e) {
            // Nếu đang mở dropdown thì vẫn cho click chuyển trang
            window.location.href = "<?= BASE_URL ?>/car_categories.php";
        });
    </script>
</body>
</html>
