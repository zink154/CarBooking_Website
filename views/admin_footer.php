<?php
#admin_footer.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TamHang Tourist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Đảm bảo footer luôn ở cuối trang */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1; /* Đẩy footer xuống dưới */
        }

        /* Footer tùy chỉnh màu nền vàng cam và chữ đen */
        .footer-custom {
            background-color: #fcb213;
            color: #000;
        }

        .footer-custom a {
            color: #000 !important;
            text-decoration: none;
        }

        .footer-custom a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Nội dung trang web ở đây -->
    </div>

    <!-- Footer luôn ở dưới cùng -->
    <footer class="footer-custom py-4">
        <div class="container">
            <div class="row">
                <!-- Logo và thông tin bản quyền -->
                <div class="col-md-6">
                    <h5 class="mb-3">TamHang Tourist</h5>
                    <p>&copy; <?= date('Y') ?> TamHang Tourist. All right reserved.</p>
                </div>

                <!-- Liên kết điều hướng -->
                <div class="col-md-3">
                    <h5 class="mb-3">Liên kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="/pages/privacy.php" class="text-light text-decoration-none">Chính sách bảo mật</a></li>
                        <li><a href="/pages/terms.php" class="text-light text-decoration-none">Điều khoản sử dụng</a></li>
                    </ul>
                </div>

                <!-- Mạng xã hội -->
                <div class="col-md-3">
                    <h5 class="mb-3">Kết nối với chúng tôi</h5>
                    <ul class="list-unstyled d-flex gap-3">
                        <li><a href="https://www.facebook.com/profile.php?id=61561758564355" target="_blank" class="text-light"><i class="bi bi-facebook"></i></a></li>
                        <li><a href="https://www.tiktok.com/@tm.hng.tourist" target="_blank" class="text-light"><i class="bi bi-tiktok"></i></a></li>
                        <li><a href="https://instagram.com" target="_blank" class="text-light"><i class="bi bi-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
