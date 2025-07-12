<?php
require_once __DIR__ . '/../config/autoload_config.php';

// Lọc dữ liệu
$filter_status = $_GET['status'] ?? 'all';
$search_name = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];
$types = '';

// Lọc theo trạng thái
if ($filter_status !== 'all') {
    $where[] = "b.status = ?";
    $params[] = $filter_status;
    $types .= 's';
}

// Lọc theo tên người dùng
if (!empty($search_name)) {
    $where[] = "u.name LIKE ?";
    $params[] = "%$search_name%";
    $types .= 's';
}

$where_clause = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Đếm tổng bản ghi để phân trang
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

// Truy vấn danh sách đơn
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

// Trạng thái
$statuses = ['all', 'booked', 'processing', 'completed', 'cancelled'];
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn đặt xe</title>
</head>
<body>
    <h2>📑 Danh sách đơn đặt xe</h2>

    <form method="GET" style="margin-bottom: 20px;">
        <label>Lọc theo trạng thái:</label>
        <select name="status" onchange="this.form.submit()">
            <?php foreach ($statuses as $s): ?>
                <option value="<?= $s ?>" <?= $filter_status === $s ? 'selected' : '' ?>>
                    <?= $s === 'all' ? 'Tất cả' : ucfirst($s) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label style="margin-left: 20px;">Tìm theo tên khách hàng:</label>
        <input type="text" name="search" value="<?= htmlspecialchars($search_name) ?>">
        <button type="submit">Tìm kiếm</button>
    </form>

    <table border="1" cellpadding="10">
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
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $row['booking_id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= $row['car_brand'] ?> (<?= $row['plate_number'] ?>)</td>
                    <td><?= $row['departure_location'] ?> → <?= $row['arrival_location'] ?></td>
                    <td><?= $row['pickup_datetime'] ?><br>→ <?= $row['return_datetime'] ?></td>
                    <td><?= number_format($row['total_price'], 0) ?> VND</td>
                    <td>
                        <?php
                        echo match($row['status']) {
                            'booked' => "<span style='color:blue'>Đã đặt</span>",
                            'processing' => "<span style='color:orange'>Đang xử lý</span>",
                            'completed' => "<span style='color:green'>Hoàn tất</span>",
                            'cancelled' => "<span style='color:red'>Đã hủy</span>",
                            default => ucfirst($row['status'])
                        };
                        ?>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">Không có đơn phù hợp.</td></tr>
        <?php endif; ?>
    </table>

    <!-- PHÂN TRANG -->
    <div style="margin-top: 20px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?status=<?= $filter_status ?>&search=<?= urlencode($search_name) ?>&page=<?= $i ?>" 
                style="margin-right: 10px; <?= $i == $page ? 'font-weight: bold;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <br><a href="dashboard.php">← Về trang quản trị</a>
</body>
</html>

<?php include __DIR__ . '/../views/footer.php'; ?>
