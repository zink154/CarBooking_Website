<?php
/**
 * bookings.php
 *
 * This script displays the list of all car bookings for admin management.
 * Features:
 *  - Filter bookings by status (all, booked, confirmed, etc.).
 *  - Search bookings by customer name.
 *  - Pagination with 10 bookings per page.
 *  - Display booking details (customer, car, route, pickup/return time, price, status).
 *  - Allows admin to update the booking status using AJAX (fetch to edit_booking_status.php).
 *
 * Requirements:
 *  - Admin must be logged in (admin_auth.php).
 *  - Database connection (db.php).
 */

require_once __DIR__ . '/../config/config.php';    // General configuration
require_once __DIR__ . '/../config/db.php';        // Database connection
require_once __DIR__ . '/../config/session.php';   // Session management
require_once __DIR__ . '/../config/admin_auth.php';// Admin authentication

// --- Retrieve filters and pagination parameters ---
$filter_status = $_GET['status'] ?? 'all';     // Booking status filter
$search_name = trim($_GET['search'] ?? '');    // Customer name search keyword
$page = max(1, intval($_GET['page'] ?? 1));    // Current page (default is 1)
$limit = 10;                                  // Rows per page
$offset = ($page - 1) * $limit;                // Offset for SQL LIMIT

// --- Build WHERE conditions based on filters ---
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

// --- Count total bookings (for pagination) ---
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

// --- Fetch bookings with user, car, and route info ---
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

// --- Booking statuses and labels ---
$statuses = ['all', 'booked', 'confirmed', 'processing', 'completed', 'cancelled'];
$labels = [
    'all' => 'Tất cả',
    'booked' => 'Đã đặt',
    'confirmed' => 'Đã xác nhận',
    'processing' => 'Đang xử lý',
    'completed' => 'Hoàn tất',
    'cancelled' => 'Đã hủy'
];
?>

<?php include __DIR__ . '/../views/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Danh sách đơn đặt xe</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">← Về trang quản trị</a>
    </div>

    <!-- Filter and search form -->
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

    <!-- Bookings Table -->
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
                    <th>Cập nhật trạng thái</th>
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
                                // Display status badge with colors
                                echo match($row['status']) {
                                    'booked' => "<span class='badge bg-primary'>Đã đặt</span>",
                                    'confirmed' => "<span class='badge bg-info'>Đã xác nhận</span>",
                                    'processing' => "<span class='badge bg-warning text-dark'>Đang xử lý</span>",
                                    'completed' => "<span class='badge bg-success'>Hoàn tất</span>",
                                    'cancelled' => "<span class='badge bg-danger'>Đã hủy</span>",
                                    default => ucfirst($row['status'])
                                };
                                ?>
                            </td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <!-- Form to update booking status -->
                                <form class="update-status-form" method="post">
                                    <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                                    <div class="d-flex gap-2">
                                        <select name="new_status" class="form-select form-select-sm">
                                            <?php foreach (['booked', 'confirmed', 'processing', 'completed', 'cancelled'] as $status): ?>
                                                <option value="<?= $status ?>" <?= $row['status'] === $status ? 'selected' : '' ?>>
                                                    <?= $labels[$status] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">✔</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center text-muted">Không có đơn phù hợp.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?status=<?= $filter_status ?>&search=<?= urlencode($search_name) ?>&page=1">«</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?status=<?= $filter_status ?>&search=<?= urlencode($search_name) ?>&page=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?status=<?= $filter_status ?>&search=<?= urlencode($search_name) ?>&page=<?= $total_pages ?>">»</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- AJAX script for updating booking status -->
<script>
document.querySelectorAll('.update-status-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('edit_booking_status.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        })
        .catch(() => alert('Lỗi khi gửi yêu cầu.'));
    });
});
</script>

<?php include __DIR__ . '/../views/footer.php'; ?>
