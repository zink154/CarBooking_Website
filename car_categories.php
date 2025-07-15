<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';
?>

<?php include __DIR__ . '/views/header.php'; ?>

    <div class="container my-5">
        <h2 class="fw-bold mb-4">DÒNG XE</h2>

        <?php
        // Mảng dữ liệu dòng xe
        $car_categories = [
            [
                'type' => '16',
                'title' => 'XE 16 CHỖ',
                'image' => 'images/car_categories/car_16.jpg',
                'desc' => 'TamHang Tourist cung cấp dịch vụ xe 16 chỗ tiện lợi và thoải mái, phù hợp cho nhóm đông người, tour, công ty, đưa đón sân bay...'
            ],
            [
                'type' => '7',
                'title' => 'XE 7 CHỖ',
                'image' => 'images/car_categories/car_7.webp',
                'desc' => 'Lý tưởng cho gia đình nhỏ hoặc nhóm bạn, xe 7 chỗ rộng rãi, tiện nghi, linh hoạt hành trình và thoải mái tối đa.'
            ],
            [
                'type' => '4',
                'title' => 'XE 4 CHỖ',
                'image' => 'images/car_categories/car_4.jpg',
                'desc' => 'Dịch vụ xe 4 chỗ dành cho 1–4 người, tiết kiệm, nhanh chóng, phù hợp cho đi công tác, cá nhân hoặc cặp đôi.'
            ],
        ];
        ?>

        <?php foreach ($car_categories as $car): ?>
            <div class="row mb-4 align-items-center">
                <div class="col-md-4">
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= $car['title'] ?>" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-8">
                    <h5 class="fw-bold"><?= htmlspecialchars($car['title']) ?></h5>
                    <p><?= htmlspecialchars($car['desc']) ?></p>
                    <a href="car_<?= $car['type'] ?>.php" class="btn btn-dark">Xem thêm</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php include __DIR__ . '/views/footer.php'; ?>
