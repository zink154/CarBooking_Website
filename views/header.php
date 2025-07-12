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
        
    </style>

</head>
<body>
    <header class="main-header">
        <!-- Thanh điều hướng -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">TamHang Tourist</a>
                
                <!-- Nút Toggle cho màn hình nhỏ -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu điều hướng -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto text-center">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">TRANG CHỦ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/users/search_car.php">GIỚI THIỆU</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="carDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                DÒNG XE
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="carDropdown"> <!-- ĐÃ BỎ text-center -->
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/book_car.php?type=4">Xe 4 chỗ</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/book_car.php?type=7">Xe 7 chỗ</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/users/book_car.php?type=16">Xe 16 chỗ</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/users/book_car.php">DỊCH VỤ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/contact.php">LIÊN HỆ</a></li>

                        <!-- Hiển thị thông tin người dùng -->
                        <?php if ($is_logged_in): ?>
                            <li class="nav-item dropdown position-relative">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" aria-expanded="false">
                                    Xin chào, <?= htmlspecialchars($user_name); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>profile.php">Hồ sơ của tôi</a></li>
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
</body>
</html>
