<?php
// car_16.php

/**
 * This page provides detailed information about the 16-seat car service.
 * Features:
 *  - Describe key features and amenities of 16-seat vehicles.
 *  - Explain why this car type is ideal for large groups.
 *  - Highlight the advantages of choosing TamHang Tourist.
 *  - Provide a call-to-action section with contact information.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Load configuration and required files
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include the website header -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Page title -->
            <h2 class="fw-bold mb-4 text-uppercase">DÒNG XE: XE 16 CHỖ</h2>

            <!-- Introduction -->
            <p class="lead"><strong>TamHang Tourist xin kính chào quý khách!</strong></p>

            <p class="text-justify">
                Là đơn vị cung cấp dịch vụ vận chuyển hành khách 
                <strong>Uy tín – Chuyên nghiệp – Giá rẻ</strong> tại Cần Thơ và các tỉnh miền Nam, 
                TamHang Tourist luôn đồng hành cùng Quý khách trên mọi hành trình 
                với phương châm <strong>“Khách hàng là trung tâm”</strong>.
            </p>

            <p class="text-justify">
                Xe 16 chỗ là giải pháp lý tưởng cho <strong>nhóm đông người</strong>, 
                phù hợp với nhu cầu di chuyển cho gia đình lớn, công ty, trường học, 
                đưa đón khách sự kiện, tham quan du lịch, hành hương, v.v.
            </p>

            <!-- Key features -->
            <h5 class="mt-4 fw-bold">Điểm Nổi Bật của Dòng Xe 16 Chỗ:</h5>

            <ol class="ps-3">
                <li class="mb-3"><strong>Sức Chứa Lớn – Không Gian Thoải Mái:</strong>
                    <ul class="ps-4">
                        <li>Chở tối đa 16 hành khách với chỗ ngồi rộng rãi, thông thoáng.</li>
                        <li>Trang bị điều hòa công suất lớn, cửa sổ mở thoáng khí.</li>
                        <li>Khoang chứa hành lý rộng, thuận tiện cho các chuyến đi xa hoặc dài ngày.</li>
                    </ul>
                </li>

                <li class="mb-3"><strong>Tiện Ích Đầy Đủ – Đáp Ứng Mọi Nhu Cầu:</strong>
                    <ul class="ps-4">
                        <li>Ghế ngồi bọc nệm êm ái, có thể ngả lưng thoải mái.</li>
                        <li>Trang bị âm thanh, đèn LED, hệ thống giảm xóc êm ái.</li>
                        <li>Phù hợp với tour du lịch, lễ hội, sự kiện, đưa đón khách đoàn...</li>
                    </ul>
                </li>

                <li class="mb-3"><strong>Lái Xe Tận Tâm – Lịch Sự – An Toàn:</strong>
                    <ul class="ps-4">
                        <li>Tài xế giàu kinh nghiệm, được đào tạo bài bản, am hiểu đường sá.</li>
                        <li>Luôn tuân thủ tốc độ, đảm bảo an toàn tuyệt đối cho hành khách.</li>
                        <li>Thái độ thân thiện, hỗ trợ hành lý và chăm sóc khách hàng tận tình.</li>
                    </ul>
                </li>
            </ol>

            <!-- Why choose TamHang Tourist -->
            <h5 class="mt-4 fw-bold">Cam Kết Dịch Vụ Từ TamHang Tourist:</h5>

            <ul class="ps-4">
                <li><strong>Giá Tốt – Không Phí Ẩn:</strong> Báo giá rõ ràng, minh bạch, không phát sinh chi phí bất ngờ.</li>
                <li><strong>Đặt Xe Dễ Dàng:</strong> Gọi tổng đài 24/7: <strong>036.727.8495 – 036.642.6365</strong> để được hỗ trợ nhanh nhất.</li>
                <li><strong>Hỗ Trợ Đa Dạng:</strong> Phục vụ thuê xe theo giờ, theo ngày, hoặc theo hành trình dài.</li>
            </ul>

            <p class="mt-4 fs-5 text-dark">
                <strong>Đặt ngay xe 16 chỗ để có chuyến đi trọn vẹn, thoải mái và an toàn cùng TamHang Tourist!</strong>
            </p>

            <!-- Call-to-action section -->
            <div class="text-center mt-5">
                <h4 class="mb-3 text-danger fw-bold">Bạn cần tư vấn lịch trình, báo giá chi tiết hoặc đặt xe ngay?</h4>
                <p class="mb-4 text-muted">
                    Đội ngũ tư vấn viên của TamHang Tourist luôn sẵn sàng hỗ trợ bạn 24/7. 
                    Liên hệ ngay để nhận ưu đãi và hỗ trợ nhanh chóng nhất!
                </p>

                <a href="contact.php" class="contact-button">
                     📩 Liên hệ ngay
                 </a>
            </div>
            
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include the website footer -->
