<?php
/**
 * FAQ Page - Frequently Asked Questions
 * 
 * This page provides answers to common questions about the car booking service.
 * It includes information on how to book a car, payment methods, cancellation policies, and more.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Load configuration
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
            <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
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

    <!-- FAQ 6 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading6">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                6. Chính sách hủy đơn đặt xe được tính như thế nào?
            </button>
        </h2>
        <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6">
            <div class="accordion-body">
                Chính sách hủy đơn đặt xe tại <strong>TamHang Tourist</strong> được áp dụng như sau:
                <ul>
                    <li>
                        <strong>Hủy trước <span style="color:#d9534f;">24 giờ</span> so với thời điểm nhận xe:</strong> 
                        Miễn phí hủy và được hoàn lại 100% số tiền đã thanh toán (nếu có).
                        <br><em><u>Ví dụ:</u></em> Nếu bạn đặt xe nhận lúc <strong>14:00 ngày 10/8</strong>, bạn hủy trước <strong>14:00 ngày 9/8</strong> sẽ được hoàn toàn bộ số tiền đã thanh toán.
                    </li>
                    <li class="mt-2">
                        <strong>Hủy trong vòng <span style="color:#d9534f;">24 giờ</span> trước thời điểm nhận xe:</strong> 
                        Có thể mất phí hủy 50% hoặc không hoàn tiền, tùy theo loại dịch vụ và tình trạng thanh toán.
                        <br><em><u>Ví dụ:</u></em> Nếu bạn đặt xe nhận lúc <strong>14:00 ngày 10/8</strong> và hủy sau <strong>14:00 ngày 9/8</strong> (ví dụ <strong>18:00 ngày 9/8</strong>), có thể bạn sẽ bị trừ 50% phí hoặc không được hoàn.
                    </li>
                    <li class="mt-2">
                        <strong>Không hủy hoặc không có mặt khi đến giờ nhận xe:</strong> 
                        Đơn sẽ bị coi là không hợp lệ và không được hoàn tiền.
                        <br><em><u>Ví dụ:</u></em> Nếu bạn không hủy và cũng không đến nhận xe vào <strong>14:00 ngày 10/8</strong>, toàn bộ số tiền sẽ không được hoàn lại.
                    </li>
                </ul>
                <p class="mt-3">
                    Vui lòng liên hệ <a href="contact.php" class="fw-bold text-decoration-none">trang liên hệ</a> 
                    hoặc gọi <strong>hotline</strong> để được hỗ trợ hủy đơn và xác nhận phí hủy.
                </p>
            </div>
        </div>
    </div>

    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include footer -->
