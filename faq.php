<?php
/**
 * FAQ Page - Frequently Asked Questions
 * 
 * This page provides answers to common questions about the car booking service.
 * It includes information on how to book a car, payment methods, cancellation policies, and more.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Load cấu hình, session, DB
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include header -->

<div class="container py-5">
    <h2 class="text-center fw-bold mb-4">Câu Hỏi Thường Gặp (FAQ)</h2>
    <p class="text-center text-muted mb-5">Giải đáp các thắc mắc phổ biến về dịch vụ đặt xe TamHang Tourist.</p>

    <div class="accordion" id="faqAccordion">

        <!-- FAQ 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeading1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                    1. Làm thế nào để đặt xe?
                </button>
            </h2>
            <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Bạn cần <strong>đăng ký tài khoản</strong> (nếu chưa có), sau đó vào mục <em>"Đặt xe"</em> để chọn 
                    <strong>tuyến đường, thời gian và loại xe</strong>. 
                    Cuối cùng, xác nhận đặt xe và tiến hành thanh toán (nếu cần).
                    <br><br>
                    Hoặc bạn có thể truy cập <strong>trang Giới thiệu</strong> (trên thanh menu) để xem <em>bảng giá, các tuyến tiêu biểu</em> 
                    và <strong>liên hệ với chúng tôi</strong> qua hotline để đặt xe trực tiếp.
                </div>
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeading2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                    2. Tôi có thể thanh toán bằng phương thức nào?
                </button>
            </h2>
            <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Hệ thống hỗ trợ <strong>chuyển khoản VietQR</strong> và <strong>thanh toán tiền mặt</strong> khi sử dụng dịch vụ.
                    Nếu bạn chọn VietQR, hệ thống sẽ cung cấp mã QR để quét và xác nhận ngay.
                </div>
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeading3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                    3. Tôi có thể hủy đơn đặt xe không?
                </button>
            </h2>
            <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Bạn có thể <strong>hủy đơn</strong> nếu đơn đặt vẫn đang ở trạng thái <em>"Đã đặt"</em> và chưa thanh toán.
                    Vào mục <em>"Lịch sử đặt xe"</em>, chọn đơn cần hủy và bấm <strong>"Hủy đơn"</strong>.
                </div>
            </div>
        </div>

        <!-- FAQ 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeading4">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                    4. Làm sao để xem lịch sử đặt xe?
                </button>
            </h2>
            <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Sau khi đăng nhập, bạn có thể xem lịch sử đặt xe trong phần <em>"Lịch sử đặt xe của tôi"</em>. 
                    Tại đây, bạn cũng có thể xem trạng thái thanh toán và đánh giá chuyến đi.
                </div>
            </div>
        </div>

        <!-- FAQ 5 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeading5">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                    5. Làm thế nào để đánh giá dịch vụ?
                </button>
            </h2>
            <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Sau khi chuyến đi được hoàn tất, bạn có thể vào <em>"Lịch sử đặt xe"</em> và nhấn nút <strong>"Đánh giá"</strong> để gửi nhận xét và chấm điểm dịch vụ.
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include footer -->
