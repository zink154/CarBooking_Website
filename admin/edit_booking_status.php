<?php
// edit_booking_status.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/admin_auth.php';

// Kiểm tra phương thức POST và tham số
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['new_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['new_status'];

    // Danh sách trạng thái hợp lệ
    $valid_statuses = ['booked', 'confirmed', 'processing', 'completed', 'cancelled'];

    if (!in_array($new_status, $valid_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ.']);
        exit;
    }

    // Cập nhật trạng thái booking
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
    $stmt->bind_param("si", $new_status, $booking_id);

    if ($stmt->execute()) {
        // Nếu booking đã hoàn tất hoặc bị hủy thì xe trở về 'available'
        if (in_array($new_status, ['completed', 'cancelled'])) {
            $update_car = $conn->prepare("
                UPDATE cars 
                SET status = 'available' 
                WHERE car_id = (SELECT car_id FROM bookings WHERE booking_id = ?)
            ");
            $update_car->bind_param("i", $booking_id);
            $update_car->execute();
            $update_car->close();
        }
        // Nếu booking đang xử lý hoặc đã xác nhận, xe chuyển sang 'in_use'
        elseif (in_array($new_status, ['processing', 'confirmed'])) {
            $update_car = $conn->prepare("
                UPDATE cars 
                SET status = 'in_use' 
                WHERE car_id = (SELECT car_id FROM bookings WHERE booking_id = ?)
            ");
            $update_car->bind_param("i", $booking_id);
            $update_car->execute();
            $update_car->close();
        }

        echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu gửi không hợp lệ.']);
}
?>
