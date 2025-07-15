<?php
require_once __DIR__ . '/config/autoload_config.php';
?>

<?php include __DIR__ . '/views/header.php'; ?>

<style>
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

<div class="container py-4">
    <h1 class="text-center text-primary">GIỚI THIỆU</h1>
    <p class="text-center">Dịch vụ thuê xe du lịch chuyên nghiệp – Xe 4 chỗ, 7 chỗ, 16 chỗ phục vụ khắp miền Tây và TP.HCM</p>

    <hr>

    <h3 class="text-center text-dark">BẢNG GIÁ</h3>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-secondary">
                <tr>
                    <th>Khoảng cách</th>
                    <th>Xe 4 chỗ</th>
                    <th>Xe 7 chỗ</th>
                    <th>Xe 16 chỗ</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1km – 30km</td><td>15.000đ</td><td>18.000đ</td><td>20.000đ</td></tr>
                <tr><td>30km – 60km</td><td>13.000đ</td><td>15.000đ</td><td>18.000đ</td></tr>
                <tr><td>60km – 99km</td><td>11.000đ</td><td>13.000đ</td><td>16.000đ</td></tr>
                <tr><td>> 100km</td><td colspan="3" class="text-danger"><strong>Liên hệ:</strong> 036.727.8495 – 036.642.6365</td></tr>
            </tbody>
        </table>
    </div>
    <p class="text-muted text-center fst-italic">* Giá ở trang này là tham khảo.</p>

    <hr>

    <h3 class="text-center text-dark">BẢNG GIÁ CÁC TUYẾN TIÊU BIỂU</h3>
    <div class="row justify-content-center mb-4">
        <div class="col-md-6 text-center">
            <img src="images/intro/price_1.jpg" alt="Bảng giá tuyến 1" class="img-fluid" style="max-width: 100%; height: auto; max-height: 800px;">
        </div>
        <div class="col-md-6 text-center">
            <img src="images/intro/price_2.jpg" alt="Bảng giá tuyến 2" class="img-fluid" style="max-width: 100%; height: auto; max-height: 800px;">
        </div>
    </div>

    <hr>

    <div class="text-center mt-5">
        <h4 class="mb-3 text-danger fw-bold">Bạn cần tư vấn lịch trình, báo giá chi tiết hoặc đặt xe ngay?</h4>
        <p class="mb-4 text-muted">Đội ngũ tư vấn viên của TamHang Tourist luôn sẵn sàng hỗ trợ bạn 24/7. Liên hệ ngay để nhận ưu đãi và hỗ trợ nhanh chóng nhất!</p>

        <a href="contact.php" class="contact-button">
            📩 Liên hệ ngay
        </a>
    </div>

</div>
<?php include __DIR__ . '/views/footer.php'; ?>

