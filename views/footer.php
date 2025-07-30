<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TamHang Tourist</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Ensure footer sticks to the bottom of the page */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1; /* Push the footer to the bottom */
        }

        /* Custom footer with yellow-orange background and black text */
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
        <!-- Main website content goes here -->
    </div>

    <!-- Footer (always at the bottom) -->
    <footer class="footer-custom py-4">
        <div class="container">
            <div class="row">
                <!-- Logo and copyright -->
                <div class="col-md-6">
                    <h5 class="mb-3">TamHang Tourist</h5>
                    <p>&copy; <?= date('Y') ?> TamHang Tourist. All rights reserved.</p>
                </div>

                <!-- Navigation links -->
                <div class="col-md-3">
                    <h5 class="mb-3">Liên kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/privacy.php">Chính sách bảo mật</a></li>
                        <li><a href="<?= BASE_URL ?>/terms.php">Điều khoản dịch vụ</a></li>
                        <li><a href="<?= BASE_URL ?>/faq.php">Câu hỏi thường gặp (FAQ)</a></li>
                    </ul>
                </div>

                <!-- Social media links -->
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
