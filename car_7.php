<?php
// car_7.php

/**
 * This page provides detailed information about the 7-seat car service.
 * Features:
 *  - Describe the advantages and amenities of 7-seat cars.
 *  - Explain why customers should choose TamHang Tourist for this type of car.
 *  - Provide contact information and a quick call-to-action link.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Autoload configuration and database connection
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include the website header -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Page title -->
            <h2 class="fw-bold mb-4 text-uppercase">DÒNG XE: XE 7 CHỖ</h2>

            <!-- Introduction -->
            <p class="lead"><strong>TamHang Tourist xin kính chào quý khách!</strong></p>

            <p class="text-justify">
                Chúng tôi là đơn vị kinh doanh cung cấp dịch vụ Du lịch 
                <strong>Uy tín – Chuyên nghiệp – Giá rẻ</strong> tại Cần Thơ và khu vực miền Nam.
                Với tiêu chí <strong>“Khách hàng là trung tâm”</strong>, chúng tôi luôn sẵn sàng phục vụ 24/7 qua tổng đài:
                <strong>036.727.8495 – 036.642.6365</strong>.
            </p>

            <p class="text-justify">
                Xe 7 chỗ là lựa chọn hoàn hảo cho nhóm bạn, gia đình nhỏ hoặc khách có nhu cầu
                không gian rộng rãi, tiện nghi trên hành trình.
            </p>

            <!-- Key features -->
            <h5 class="mt-4 fw-bold">Ưu Điểm Nổi Bật của Xe 7 Chỗ:</h5>

            <ol class="ps-3">
                <li class="mb-3"><strong>Không Gian Rộng – Tiện Nghi Đầy Đủ:</strong>
                    <ul class="ps-4">
                        <li>Phù hợp cho 5–7 người, mang lại sự thoải mái trong suốt chuyến đi.</li>
                        <li>Ghế ngồi bọc da, gập linh hoạt để chứa hành lý hoặc tăng chỗ ngồi.</li>
                        <li>Trang bị máy lạnh mạnh mẽ, âm thanh hiện đại, cửa trượt tiện dụng.</li>
                    </ul>
                </li>

                <li class="mb-3"><strong>Tài Xế Tận Tâm – Kinh Nghiệm:</strong>
                    <ul class="ps-4">
                        <li>Đội ngũ lái xe thân thiện, nhiệt tình, am hiểu địa phương.</li>
                        <li>Luôn hỗ trợ khách khi cần: hướng dẫn điểm đến, hỗ trợ hành lý, điều chỉnh lộ trình linh hoạt.</li>
                    </ul>
                </li>

                <li class="mb-3"><strong>Dịch Vụ Linh Hoạt – Đa Dạng:</strong>
                    <ul class="ps-4">
                        <li>Phục vụ nhu cầu đưa đón sân bay, đi tỉnh, du lịch, công tác, đám cưới, đưa đón người thân...</li>
                        <li>Có thể thuê xe theo giờ, nửa ngày hoặc cả ngày với mức giá hợp lý.</li>
                    </ul>
                </li>
            </ol>

            <!-- Reasons to choose -->
            <h5 class="mt-4 fw-bold">Lý Do Chọn TamHang Tourist:</h5>

            <ul class="ps-4">
                <li><strong>Phục Vụ 24/7:</strong> Dù sáng sớm hay khuya muộn, chúng tôi luôn sẵn sàng.</li>
                <li><strong>Xe Đẹp – Đời Mới – Vệ Sinh Sạch Sẽ:</strong> Đảm bảo trải nghiệm tốt nhất cho quý khách.</li>
                <li><strong>Chi Phí Rõ Ràng:</strong> Báo giá trước – không phát sinh phụ thu bất ngờ.</li>
            </ul>
  
            <p class="mt-4 fs-5 text-dark">
                <strong>Liên hệ ngay hôm nay để đặt xe 7 chỗ nhanh chóng và trải nghiệm dịch vụ chất lượng cùng TamHang Tourist!</strong>
            </p>

            <!-- Call-to-action -->
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
