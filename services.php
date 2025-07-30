<?php
// services.php
require_once __DIR__ . '/config/autoload_config.php'; // Load configuration and required files
include __DIR__ . '/views/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ - TamHang Tourist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .service-hero {
            background: url('<?= BASE_URL ?>/images/index/Toyota-Innova-1.webp') center/cover no-repeat;
            color: #000; /* Chữ màu đen */
            text-align: center;
            padding: 100px 20px;
        }
        .service-hero h1 {
            font-size: 3rem;
            font-weight: 800;
        }
        .service-hero p {
            font-size: 1.2rem;
        }
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .service-icon {
            font-size: 3rem;
            color: #fcb213;
        }
        .cta-section {
            background-color: #fcb213;
            text-align: center;
            padding: 40px 20px;
            border-radius: 12px;
        }
        .cta-section h2 {
            font-weight: 700;
        }
        .cta-section a.btn {
            font-size: 18px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="service-hero">
    <h1>Dịch vụ của TamHang Tourist</h1>
    <p>Chúng tôi mang đến giải pháp đặt xe nhanh chóng, tiện lợi và giá cả minh bạch.</p>
</section>

<!-- Services List -->
<div class="container my-5">
    <div class="row text-center mb-4">
        <h2 class="fw-bold">Dịch vụ Nổi Bật</h2>
        <p class="text-muted">Đa dạng dịch vụ cho mọi nhu cầu di chuyển của bạn.</p>
    </div>

    <div class="row g-4">
        <!-- Service 1 -->
        <div class="col-md-3">
            <div class="card service-card p-3 text-center border-0 shadow">
                <div class="service-icon mb-3">
                    <i class="fas fa-route"></i>
                </div>
                <h5>Đặt xe tuyến cố định</h5>
                <p class="text-muted">Các tuyến phổ biến: Cần Thơ – Cà Mau, Cần Thơ – Sóc Trăng...</p>
            </div>
        </div>

        <!-- Service 2 -->
        <div class="col-md-3">
            <div class="card service-card p-3 text-center border-0 shadow">
                <div class="service-icon mb-3">
                    <i class="fas fa-car"></i>
                </div>
                <h5>Xe tự lái & có tài xế</h5>
                <p class="text-muted">Lựa chọn theo nhu cầu, đảm bảo tiện lợi và an toàn.</p>
            </div>
        </div>

        <!-- Service 3 -->
        <div class="col-md-3">
            <div class="card service-card p-3 text-center border-0 shadow">
                <div class="service-icon mb-3">
                    <i class="fas fa-plane-arrival"></i>
                </div>
                <h5>Đưa đón sân bay</h5>
                <p class="text-muted">Đón trả đúng giờ, dịch vụ chuyên nghiệp 24/7.</p>
            </div>
        </div>

        <!-- Service 4 -->
        <div class="col-md-3">
            <div class="card service-card p-3 text-center border-0 shadow">
                <div class="service-icon mb-3">
                    <i class="fas fa-bus"></i>
                </div>
                <h5>Thuê xe du lịch</h5>
                <p class="text-muted">Xe 4, 7, 16, ... chỗ phù hợp cho nhóm bạn, gia đình.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="container my-5">
    <div class="cta-section">
        <h2>Bạn muốn đặt xe ngay?</h2>
        <p class="mb-4">Chỉ vài bước đơn giản để khởi hành chuyến đi an toàn và tiện lợi.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/users/booking_form.php" class="btn btn-outline-dark px-4 py-2">ĐẶT XE NGAY</a>
            <a href="<?= BASE_URL ?>/intro.php" class="btn btn-outline-dark px-4 py-2">XEM BẢNG GIÁ</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
