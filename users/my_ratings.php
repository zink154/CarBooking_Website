<?php
// my_ratings.php

/**
 * This page displays all ratings/reviews created by the logged-in user.
 * Features:
 *  - Display total ratings and average score.
 *  - Search ratings by car name or license plate.
 *  - Pagination to limit the number of ratings displayed per page.
 *  - Show details: booking ID, car, score, comment, and date.
 */

require_once __DIR__ . '/../config/auth.php'; // Ensure the user is authenticated

$user_id = $_SESSION['user_id'];

// --- Get search and pagination parameters ---
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10; // Number of ratings per page
$offset = ($page - 1) * $limit;

// --- Build the WHERE clause dynamically ---
$where = "WHERE r.user_id = ?";
$params = [$user_id];
$types = 'i';

// Add search condition if applicable
if (!empty($search)) {
    $where .= " AND (c.car_name LIKE ? OR c.plate_number LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}

// --- Count total ratings for pagination ---
$count_sql = "
    SELECT COUNT(*) AS total
    FROM ratings r
    JOIN cars c ON r.car_id = c.car_id
    $where
";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// --- Fetch ratings with limit and offset ---
$sql = "
    SELECT r.*, c.car_name, c.plate_number, b.booking_id
    FROM ratings r
    JOIN bookings b ON r.booking_id = b.booking_id
    JOIN cars c ON r.car_id = c.car_id
    $where
    ORDER BY r.created_at DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// --- Fetch rating statistics (total count and average score) ---
$stat = $conn->prepare("SELECT COUNT(*) AS total, AVG(score) AS avg FROM ratings WHERE user_id = ?");
$stat->bind_param("i", $user_id);
$stat->execute();
$stat_result = $stat->get_result()->fetch_assoc();
$totalRatings = $stat_result['total'];
$avgScore = $stat_result['avg'] ? number_format($stat_result['avg'], 1) : 'Chưa có';
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container py-4">
    <h3 class="fw-bold mb-3">Đánh giá của tôi</h3>
    <p><strong>Tổng số đánh giá:</strong> <?= $totalRatings ?> | <strong>Điểm trung bình:</strong> <?= $avgScore ?> ⭐</p>

    <!-- Search form -->
    <form method="get" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="Tìm theo tên xe hoặc biển số..." 
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 Tìm kiếm</button>
        </div>
        <?php if (!empty($search)): ?>
        <div class="col-md-2">
            <a href="my_ratings.php" class="btn btn-secondary w-100">Reset</a>
        </div>
        <?php endif; ?>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <!-- Ratings table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Xe</th>
                        <th>Số sao</th>
                        <th>Nhận xét</th>
                        <th>Ngày đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $row['booking_id'] ?></td>
                            <td><?= htmlspecialchars($row['car_name']) ?> (<?= $row['plate_number'] ?>)</td>
                            <td><?= $row['score'] ?> ⭐</td>
                            <td><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=1">«</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $total_pages ?>">»</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-info">Bạn chưa có đánh giá nào.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../views/footer.php'; ?>
