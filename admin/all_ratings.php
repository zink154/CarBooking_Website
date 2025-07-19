<?php
// all_ratings.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];
$types = '';

if (!empty($search)) {
    $where = "WHERE u.name LIKE ? OR c.car_brand LIKE ? OR c.plate_number LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
    $types = str_repeat('s', count($params));
}

// Đếm tổng số dòng
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
$total_pages = ceil($total_rows / $limit);

// Lấy dữ liệu
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

// Thống kê chung
$stats = $conn->query("SELECT COUNT(*) AS total, AVG(score) AS avg FROM ratings")->fetch_assoc();
$totalRatings = $stats['total'];
$avgScore = $stats['avg'] ? number_format($stats['avg'], 1) : 'Chưa có';
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Tất cả đánh giá từ khách hàng</h3>
        <a href="dashboard.php" class="btn btn-outline-secondary">← Quay về Dashboard</a>
    </div>

    <p><strong>Tổng số đánh giá:</strong> <?= $totalRatings ?> | <strong>Điểm trung bình:</strong> <?= $avgScore ?> ⭐</p>

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
