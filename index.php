<?php
// index.php

/**
 * Homepage of TamHang Tourist
 * Features:
 *  - Display a welcoming message and company logo.
 *  - Show a Bootstrap carousel with car images.
 *  - Provide an introduction about TamHang Tourist.
 *  - Include an embedded YouTube video for additional branding.
 */

require_once __DIR__ . '/config/config.php';   // Load configuration constants
require_once __DIR__ . '/config/session.php';  // Start user session
?>

<?php include __DIR__ . '/views/header.php'; ?> <!-- Include website header -->

<style>
    /* Blink animation for "GIỚI THIỆU VỀ CHÚNG TÔI" title */
    .blink {
        animation: blinkText 2s infinite;
    }
    @keyframes blinkText {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
</style>

<!-- Welcome Section -->
<div class="container mt-5 text-center">
    <!-- Title and introduction -->
    <h1 class="fw-bold">Chào mừng đến với TamHang Tourist</h1>
    <p>Hệ thống giúp bạn đặt xe nhanh chóng, tiện lợi, và an toàn.</p>

    <!-- Company logo -->
    <img src="images/index/logo.jfif" alt="Logo" class="my-3" style="max-height: 200px;">

    <!-- Carousel Start -->
    <div id="mainCarousel" class="carousel slide mx-auto mt-4">
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <img src="images/index/car1.jpg" class="d-block w-100 rounded" alt="Xe 1">
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item">
                <img src="images/index/car2.png" class="d-block w-100 rounded" alt="Xe 2">
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item">
                <img src="images/index/car3.webp" class="d-block w-100 rounded" alt="Xe 3">
            </div>
        </div>

        <!-- Carousel controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
            <span class="visually-hidden">Trước</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
            <span class="visually-hidden">Sau</span>
        </button>
    </div>
    <!-- Carousel End -->
</div>

<!-- Introduction and Video Section -->
<div class="container my-5 mb-5">
  <div class="row align-items-center">
    <!-- Introduction text -->
    <div class="col-md-6">
      <h3 class="fw-bold text-dark blink">GIỚI THIỆU VỀ CHÚNG TÔI</h3>
      <div style="text-align: justify;">
        <p><strong>TamHang Tourist</strong> xin kính chào quý khách!</p>
        <p>
          Chúng tôi là đơn vị kinh doanh cung cấp dịch vụ Du lịch Uy tín – Chuyên nghiệp – 
          Giá rẻ tại Cần Thơ cũng như các tỉnh lân cận thuộc khu vực miền Nam.
        </p>
        <p>
          Quý khách có thể đặt xe nhanh chóng qua tổng đài Hotline: 
          <strong>036.727.8495</strong> – <strong>036.642.6365</strong>, chúng tôi phục vụ 24/7.
        </p>
        <p>
          Với phương châm <em>“Khách hàng là trung tâm”</em>, TamHang Tourist luôn cung cấp dịch vụ 
          chất lượng cao, đảm bảo an toàn và thoải mái trên mọi hành trình.
        </p>
        <p>
          Chúng tôi sở hữu đội ngũ tài xế được đào tạo bài bản, giàu kinh nghiệm, tận tâm phục vụ 
          khách hàng. Giá cả cạnh tranh, minh bạch, cam kết mang đến trải nghiệm tốt nhất cho Quý khách.
        </p>
      </div>
    </div>

    <!-- Introduction video -->
    <div class="col-md-6">
      <div class="ratio ratio-16x9">
        <iframe 
          src="https://www.youtube.com/embed/AQn8VTHTUmM" 
          title="Video giới thiệu TamHang Tourist"
          allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>

  <!-- FAQ Button -->
  <div class="text-center mt-4">
      <!-- Link to FAQ page -->
      <a href="faq.php" class="btn btn-warning btn-lg">
          ❓ Xem FAQ
      </a>
  </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?> <!-- Include website footer -->
