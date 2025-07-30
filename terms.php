<?php
// terms.php

/**
 * Terms of Service Page
 * 
 * This page provides detailed terms and conditions for using the TamHang Tourist car booking service.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Load configs, session, and DB
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include the website header -->

<!-- SEO Meta Tags -->
<title>Điều Khoản Dịch Vụ - TamHang Tourist</title>
<meta name="description" content="Xem các điều khoản và quy định khi sử dụng dịch vụ đặt xe của TamHang Tourist.">
<meta name="keywords" content="điều khoản dịch vụ, TamHang Tourist, đặt xe, chính sách, quy định">
<meta name="robots" content="index, follow">

<div class="container py-5">
    <h2 class="text-center fw-bold mb-4">Điều Khoản Dịch Vụ</h2>
    <p class="text-muted text-center mb-5">
        Khi sử dụng website và dịch vụ của TamHang Tourist, bạn đồng ý với các điều khoản sau đây.
    </p>

    <!-- General provisions -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">1. Quy Định Chung</h4>
        <p>
            Bằng việc truy cập website, bạn đồng ý với các chính sách và điều khoản được nêu tại đây.
            Nếu không đồng ý, vui lòng không sử dụng dịch vụ.
        </p>
    </div>

    <!-- User rights and obligations -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">2. Quyền & Nghĩa Vụ Của Người Dùng</h4>
        <ul>
            <li>Cung cấp thông tin chính xác khi đăng ký và đặt xe.</li>
            <li>Thanh toán đúng hạn cho các dịch vụ đã sử dụng.</li>
            <li>Không lạm dụng dịch vụ để vi phạm pháp luật.</li>
        </ul>
    </div>

    <!-- Booking and cancel -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">3. Quy Định Về Đặt Xe & Hủy Đơn</h4>
        <p>
            Đơn đặt xe sẽ được xác nhận sau khi hệ thống hoặc tổng đài thông báo thành công.
            <br><strong>Chính sách hủy đơn:</strong>
            <ul>
                <li>
                    <strong>Hủy trước 24 giờ so với thời điểm nhận xe:</strong> 
                    Miễn phí hủy và được hoàn lại 100% số tiền đã thanh toán (nếu có).
                </li>
                <li>
                    <strong>Hủy trong vòng 24 giờ trước thời điểm nhận xe:</strong> 
                    Có thể mất phí hủy 50% hoặc không hoàn tiền, tùy theo loại dịch vụ và tình trạng thanh toán.
                </li>
                <li>
                    <strong>Không hủy hoặc không có mặt khi đến giờ nhận xe:</strong> 
                    Đơn sẽ bị coi là không hợp lệ và không được hoàn tiền.
                </li>
            </ul>
        </p>
    </div>

    <!-- Responsibility of TamHang Tourist -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">4. Trách Nhiệm Của TamHang Tourist</h4>
        <p>
            Chúng tôi cam kết cung cấp xe đúng thời gian, đảm bảo an toàn và chất lượng dịch vụ. 
            Tuy nhiên, TamHang Tourist không chịu trách nhiệm cho các tình huống bất khả kháng như thiên tai, tắc đường hoặc sự cố ngoài ý muốn.
        </p>
    </div>

    <!-- Payment Terms -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">5. Quy Định Thanh Toán</h4>
        <ul>
            <li>Thanh toán bằng chuyển khoản VietQR hoặc tiền mặt.</li>
            <li>Với VietQR, đơn đặt xe chỉ được xử lý khi xác nhận thanh toán thành công.</li>
        </ul>
    </div>

    <!-- Changes to terms -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">6. Thay Đổi Điều Khoản</h4>
        <p>
            TamHang Tourist có thể thay đổi hoặc cập nhật điều khoản mà không cần báo trước. 
            Phiên bản cập nhật sẽ được đăng trên Website hoặc các trang mạng xã hội của chúng tôi.
        </p>
    </div>

    <!-- Contact -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">7. Liên Hệ</h4>
        <p>
            Nếu có câu hỏi liên quan, vui lòng <a href="contact.php" class="fw-bold text-decoration-none">liên hệ với chúng tôi</a>.
        </p>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include the website footer -->
