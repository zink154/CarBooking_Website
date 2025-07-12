<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!$name || !$email || !$subject || !$message) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } else {
        $success = "Liên hệ của bạn đã được gửi thành công!";
    }
}
?>

<?php include 'views/header.php'; ?>

<!-- Khung liên hệ và thông tin -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow p-4">
                <div class="row">
                    <!-- Cột trái: Thông tin liên hệ -->
                    <div class="col-md-5 d-flex align-items-center border-end" style="padding-right: 30px;">
                        <div class="w-100">
                            <h5 class="mb-3 text-center">THÔNG TIN LIÊN HỆ</h5>
                            <p class="text-center fw-bold">NHÀ XE TAMHANG TOURIST</p>
                            <p class="text-center">Địa chỉ: 368C, Khu vực I, Ba Láng, Cái Răng, Cần Thơ Can Tho, Vietnam</p>
                            <p class="text-center">Hotline: 036.727.8495 – 036.642.6365</p>
                            <p class="text-center">Email: tamhangtourist83@gmail.com</p>
                        </div>
                    </div>

                    <!-- Cột phải: Form liên hệ -->
                    <div class="col-md-7 ps-md-4">
                        <h4 class="text-center mb-4">Liên hệ với chúng tôi</h4>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success; ?></div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger"><?= $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Tiêu đề</label>
                                <input type="text" name="subject" id="subject" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Nội dung</label>
                                <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Gửi liên hệ</button>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>

<!-- BẢN ĐỒ -->
<div class="container pb-5">
    <h5 class="mb-3 text-center fw-bold">BẢN ĐỒ</h5>
    <div class="mx-auto" style="max-width: 700px;">
        <div class="ratio ratio-16x9 rounded shadow-sm">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d982.3387167672208!2d105.7456539285195!3d9.987516999379759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zOcKwNTknMTUuMSJOIDEwNcKwNDQnNDYuNyJF!5e0!3m2!1sen!2s!4v1752288500135!5m2!1sen!2s" 
                style="border:0;" allowfullscreen="" loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
