<?php
// privacy.php

/**
 * Privacy Policy Page
 * 
 * This page provides a detailed privacy policy for TamHang Tourist,
 * explaining how user data is collected, used, stored, and protected.
 */

require_once __DIR__ . '/config/autoload_config.php'; // Load configs, session, and DB
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include the website header -->

<!-- SEO Meta Tags -->
<title>Chính Sách Bảo Mật - TamHang Tourist</title>
<meta name="description" content="Tìm hiểu cách TamHang Tourist thu thập, sử dụng và bảo vệ thông tin cá nhân khi bạn sử dụng dịch vụ đặt xe của chúng tôi.">
<meta name="keywords" content="chính sách bảo mật, TamHang Tourist, đặt xe an toàn, bảo vệ dữ liệu, thông tin cá nhân">
<meta name="robots" content="index, follow">

<div class="container py-5">
    <h2 class="text-center fw-bold mb-4">Chính Sách Bảo Mật</h2>
    <p class="text-muted text-center mb-5">
        TamHang Tourist cam kết bảo vệ thông tin cá nhân của khách hàng, tuân thủ quy định pháp luật 
        và đảm bảo sự minh bạch trong việc thu thập và sử dụng dữ liệu.
    </p>

    <!-- Thông tin thu thập -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">1. Thông Tin Chúng Tôi Thu Thập</h4>
        <ul>
            <li><strong>Thông tin cá nhân:</strong> Họ tên, email, số điện thoại, địa chỉ, tài khoản đăng nhập.</li>
            <li><strong>Thông tin đặt xe:</strong> Tuyến đường, loại xe, thời gian nhận/trả xe, chi phí.</li>
            <li><strong>Thông tin thanh toán:</strong> Phương thức thanh toán (VietQR, tiền mặt), số tiền thanh toán.</li>
            <li><strong>Thông tin kỹ thuật:</strong> Địa chỉ IP, trình duyệt, thiết bị và cookies.</li>
        </ul>
    </div>

    <!-- Mục đích sử dụng -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">2. Mục Đích Sử Dụng Dữ Liệu</h4>
        <ul>
            <li>Xác nhận và xử lý đơn đặt xe.</li>
            <li>Gửi thông báo liên quan đến dịch vụ, khuyến mãi, và xác thực tài khoản.</li>
            <li>Cải thiện chất lượng dịch vụ và trải nghiệm người dùng.</li>
            <li>Đáp ứng yêu cầu pháp lý hoặc cơ quan nhà nước khi cần thiết.</li>
        </ul>
    </div>

    <!-- Lưu trữ và bảo mật -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">3. Lưu Trữ & Bảo Mật Dữ Liệu</h4>
        <p>
            Dữ liệu cá nhân của bạn được lưu trữ trên hệ thống cơ sở dữ liệu của chúng tôi với biện pháp bảo mật như:
            <strong>mã hóa mật khẩu (password_hash), kết nối HTTPS</strong> và tường lửa bảo vệ.
            Chúng tôi không chia sẻ dữ liệu với bên thứ ba trừ khi có sự đồng ý của bạn hoặc yêu cầu từ pháp luật.
        </p>
    </div>

    <!-- Cookies -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">4. Cookies & Theo Dõi</h4>
        <p>
            Website có thể sử dụng cookies để lưu thông tin đăng nhập và lịch sử tìm kiếm xe, nhằm nâng cao trải nghiệm. 
            Bạn có thể tắt cookies trên trình duyệt, nhưng một số chức năng có thể không hoạt động đầy đủ.
        </p>
    </div>

    <!-- Quyền của người dùng -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">5. Quyền Của Người Dùng</h4>
        <p>
            Bạn có quyền yêu cầu chỉnh sửa, xóa dữ liệu cá nhân hoặc yêu cầu ngừng nhận email từ chúng tôi.
            Vui lòng <a href="contact.php" class="fw-bold text-decoration-none">liên hệ</a> để được hỗ trợ.
        </p>
    </div>

    <!-- Liên hệ -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark">6. Liên Hệ</h4>
        <p>
            Mọi câu hỏi liên quan đến chính sách bảo mật xin gửi về email: 
            <a href="mailto:tamhangtourist83@gmail.com">tamhangtourist83@gmail.com</a>
            hoặc qua <a href="contact.php" class="fw-bold">trang liên hệ</a>.
        </p>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include the website footer -->
