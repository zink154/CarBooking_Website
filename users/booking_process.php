<?php
// booking_process.php

/**
 * This script handles the booking process when a user submits the booking form.
 * Enhancements:
 *  - Validate that user is logged in.
 *  - Check if the selected car_id and route_id exist in the database.
 *  - Validate that pickup_datetime is earlier than return_datetime.
 *  - Insert a new booking record if all conditions are met.
 *  - Update the car status to 'in_use' after booking.
 *  - Redirect to the payment page upon success.
 * 
 * Requirements:
 *  - User must be authenticated (auth.php).
 *  - Database connection and session must be available.
 */
require_once __DIR__ . '/../config/auth.php'; // Ensure the user is authenticated

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- Retrieve data from POST ---
    $user_id = $_SESSION['user_id'] ?? null;
    $route_id = intval($_POST['route_id'] ?? 0);
    $car_id = intval($_POST['car_id'] ?? 0);
    $pickup_datetime = $_POST['pickup_datetime'] ?? '';
    $return_datetime = $_POST['return_datetime'] ?? '';
    $total_price = floatval($_POST['total_price'] ?? 0);

    // --- Check if user is logged in ---
    if (!$user_id) {
        echo "Bạn cần đăng nhập để đặt xe."; // "You need to log in to make a booking."
        exit();
    }

    // --- Validate that pickup_datetime < return_datetime ---
    if (strtotime($pickup_datetime) >= strtotime($return_datetime)) {
        echo "❌ Ngày nhận xe phải nhỏ hơn ngày trả xe."; // "Pickup date must be earlier than return date."
        exit();
    }

    // --- Validate that the route exists ---
    $routeCheck = $conn->prepare("SELECT route_id FROM routes WHERE route_id = ? AND status = 'available'");
    $routeCheck->bind_param("i", $route_id);
    $routeCheck->execute();
    $routeExists = $routeCheck->get_result()->num_rows > 0;
    $routeCheck->close();

    if (!$routeExists) {
        echo "❌ Tuyến đường không tồn tại hoặc không còn hoạt động."; // "Route does not exist or is unavailable."
        exit();
    }

    // --- Validate that the car exists ---
    $carCheck = $conn->prepare("SELECT car_id FROM cars WHERE car_id = ? AND status = 'available'");
    $carCheck->bind_param("i", $car_id);
    $carCheck->execute();
    $carExists = $carCheck->get_result()->num_rows > 0;
    $carCheck->close();

    if (!$carExists) {
        echo "❌ Xe không tồn tại hoặc không còn sẵn sàng."; // "Car does not exist or is unavailable."
        exit();
    }

    // --- Insert a new booking record ---
    $stmt = $conn->prepare("
        INSERT INTO bookings (user_id, car_id, route_id, pickup_datetime, return_datetime, total_price) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiissd", $user_id, $car_id, $route_id, $pickup_datetime, $return_datetime, $total_price);

    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id;

        // --- Update the car's status to 'in_use' ---
        $updateCar = $conn->prepare("UPDATE cars SET status = 'in_use' WHERE car_id = ?");
        $updateCar->bind_param("i", $car_id);
        $updateCar->execute();
        $updateCar->close();

        // --- Redirect user to payment page ---
        header("Location: " . BASE_URL . "/users/payment.php?booking_id=" . $booking_id);
        exit();
    } else {
        // Error inserting booking
        echo "Đã có lỗi xảy ra khi đặt xe: " . $stmt->error;
    }
    $stmt->close();
} else {
    // If not a POST request, redirect to booking form
    header("Location: booking_form.php");
    exit();
}
