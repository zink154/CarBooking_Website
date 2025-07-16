<?php
require_once __DIR__ . '/../config/autoload_config.php';

$filter_status = $_GET['status'] ?? 'all';
$search_name = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];
$types = '';

if ($filter_status !== 'all') {
    $where[] = "b.status = ?";
    $params[] = $filter_status;
    $types .= 's';
}

if (!empty($search_name)) {
    $where[] = "u.name LIKE ?";
    $params[] = "%$search_name%";
    $types .= 's';
}

$where_clause = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$count_sql = "
    SELECT COUNT(*) AS total
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    $where_clause
";
$count_stmt = $conn->prepare($count_sql);
if ($types) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "
    SELECT b.*, u.name AS user_name, c.car_brand, c.plate_number, r.departure_location, r.arrival_location
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN cars c ON b.car_id = c.car_id
    JOIN routes r ON b.route_id = r.route_id
    $where_clause
    ORDER BY b.created_at DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Trạng thái và nhãn tiếng Việt
$statuses = ['all', 'booked', 'processing', 'completed', 'cancelled'];
$labels = [
    'all' => 'Tất cả',
    'booked' => 'Đã đặt',
    'processing' => 'Đang xử lý',
    'completed' => 'Hoàn tất',
    'cancelled' => 'Đã hủy'
];
?>

<?php include __DIR__ . '/../views/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn đặt xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Danh sách đơn đặt xe</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">← Về trang quản trị</a>
    </div>

    <form class="row g-3 align-items-end mb-4" method="GET">
        <div class="col-md-3">
            <label class="form-label">Lọc theo trạng thái</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>" <?= $filter_status === $s ? 'selected' : '' ?>>
                        <?= $labels[$s] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tìm theo tên khách hàng</label>
            <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search_name) ?>" placeholder="Nhập tên khách hàng...">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-yellow w-100">🔍 Tìm kiếm</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle bg-white shadow-sm">
            <thead class="table-primary text-center">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Xe</th>
                    <th>Tuyến</th>
                    <th>Thời gian</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center">#<?= $row['booking_id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= $row['car_brand'] ?> (<?= $row['plate_number'] ?>)</td>
                            <td><?= $row['departure_location'] ?> → <?= $row['arrival_location'] ?></td>
                            <td><?= $row['pickup_datetime'] ?><br>→ <?= $row['return_datetime'] ?></td>
                            <td class="text-end"><?= number_format($row['total_price'], 0) ?> VND</td>
                            <td class="text-center">
                                <?php
                                echo match($row['status']) {
                                    'booked' => "<span class='badge bg-primary'>Đã đặt</span>",
                                    'processing' => "<span class='badge bg-warning text-dark'>Đang xử lý</span>",
                                    'completed' => "<span class='badge bg-success'>Hoàn tất</span>",
                                    'cancelled' => "<span class='badge bg-danger'>Đã hủy</span>",
                                    default => ucfirst($row['status'])
                                };
                                ?>
                            </td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted">Không có đơn phù hợp.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PHÂN TRANG -->
    <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?status=<?= $filter_status ?>&search=<?= urlencode($search_name) ?>&page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
</body>
</html>
<?php include __DIR__ . '/../views/admin_footer.php'; ?>
