<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';
?>

<?php include 'views/header.php'; ?>

<!-- Khung chỉ còn Thông tin liên hệ -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow p-4">
                <div class="text-center">
                    <h5 class="mb-3 fw-bold">THÔNG TIN LIÊN HỆ</h5>
                    <p class="fw-bold">NHÀ XE TAMHANG TOURIST</p>
                    <p>Địa chỉ: 368C, Khu vực I, Ba Láng, Cái Răng, Cần Thơ, Vietnam</p>
                    <p>Hotline: 036.727.8495 – 036.642.6365</p>
                    <p>Email: tamhangtourist83@gmail.com</p>
                    <div class="mt-3">
                        <img src="<?= BASE_URL ?>/images/logo.jfif" alt="TamHang Logo" style="max-width: 120px;">
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
