<?php
/**
 * all_ratings.php
 *
 * This script displays all customer ratings (reviews) for vehicles.
 * Features:
 *  - Search functionality (filter by user name, car brand, or plate number).
 *  - Pagination (10 ratings per page).
 *  - Displays average score and total number of ratings.
 *  - Data is fetched from the 'ratings', 'users', and 'cars' tables.
 *  - Uses prepared statements to prevent SQL injection.
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // Load configuration settings
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session management
require_once __DIR__ . '/../config/admin_auth.php';// Ensure only admin can access this page

// --- Search and Pagination Setup ---
$search = trim($_GET['search'] ?? '');  // Search keyword (if any)
$page = max(1, intval($_GET['page'] ?? 1));  // Current page (default: 1)
$limit = 10;                               // Number of rows per page
$offset = ($page - 1) * $limit;            // Offset for SQL LIMIT

$where = '';
$params = [];
$types = '';

// If a search term is provided, build the WHERE clause
if (!empty($search)) {
    $where = "WHERE u.name LIKE ? OR c.car_brand LIKE ? OR c.plate_number LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
    $types = str_repeat('s', count($params)); // 's' for string type
}

// --- Count total rows (for pagination) ---
$count_sql = "
    SELECT COUNT(*) AS total
    FROM ratings r
    JOIN users u ON r.user_id = u.user_id
    JOIN cars c ON r.car_id = c.car_id
    $where
";
$count_stmt = $conn->prepare($count_sql);
if (!empty($where)) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);  // Total pages based on rows

// --- Fetch ratings data ---
$sql = "
    SELECT r.*, u.name AS user_name, c.car_name, c.plate_number 
    FROM ratings r
    JOIN users u ON r.user_id = u.user_id
    JOIN cars c ON r.car_id = c.car_id
    $where
    ORDER BY r.created_at DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $conn->prepare($sql);
if (!empty($where)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// --- Fetch overall statistics (total ratings & average score) ---
$stats = $conn->query("SELECT COUNT(*) AS total, AVG(score) AS avg FROM ratings")->fetch_assoc();
$totalRatings = $stats['total'];
$avgScore = $stats['avg'] ? number_format($stats['avg'], 1) : 'Chưa có'; // Display "Chưa có" if no ratings
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Tất cả đánh giá từ khách hàng</h3>
        <div>
            <button onclick="window.history.back();" class="btn btn-outline-secondary me-2">← Quay lại</button>
            <a href="users_management.php" class="btn btn-outline-primary">👤 Quản lý người dùng</a>
        </div>
    </div>

    <!-- Display total ratings and average score -->
    <p><strong>Tổng số đánh giá:</strong> <?= $totalRatings ?> | <strong>Điểm trung bình:</strong> <?= $avgScore ?> ⭐</p>

    <!-- Search form -->
    <form method="get" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên user hoặc xe..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 Tìm kiếm</button>
        </div>
        <?php if (!empty($search)): ?>
        <div class="col-md-2">
            <a href="all_ratings.php" class="btn btn-secondary w-100">Reset</a>
        </div>
        <?php endif; ?>
    </form>

    <!-- Ratings Table -->
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>Người dùng</th>
                        <th>Xe</th>
                        <th>Số sao</th>
                        <th>Nhận xét</th>
                        <th>Ngày đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
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
        <div class="alert alert-info">Chưa có đánh giá nào.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../views/footer.php'; ?>
