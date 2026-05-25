<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

$user = getLoggedInUser();

if (!$user || empty($user['customer_id'])) {
    header("Location: login.php?message=Please log in to complete your reservation.");
    exit;
}

$customer_id = (int)$user['customer_id'];

$stmt = $pdo->prepare("
    SELECT customer_id 
    FROM customers 
    WHERE customer_id = :customer_id
    LIMIT 1
");
$stmt->execute(['customer_id' => $customer_id]);

if (!$stmt->fetch()) {
    logoutUser();
    header("Location: login.php?message=Session expired. Please log in again.");
    exit;
}

$room_id  = $_SESSION['room_id'] ?? null;
$checkin  = $_SESSION['checkin'] ?? null;
$checkout = $_SESSION['checkout'] ?? null;
$guests   = $_SESSION['guests'] ?? null;

if (!$room_id || !$checkin || !$checkout || !$guests) {
    header("Location: index.php");
    exit;
}

$checkinDate = new DateTime($checkin);
$checkoutDate = new DateTime($checkout);
$nights = $checkinDate->diff($checkoutDate)->days;

if ($nights <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        r.room_id,
        rt.price_per_night,
        rt.max_occupancy
    FROM rooms r
    JOIN room_types rt ON r.room_type_id = rt.room_type_id
    WHERE r.room_id = :room_id
      AND rt.max_occupancy >= :guests
      AND r.room_id NOT IN (
          SELECT room_id
          FROM reservations
          WHERE check_in_date < :checkout
            AND check_out_date > :checkin
      )
    LIMIT 1
");

$stmt->execute([
    'room_id' => $room_id,
    'guests' => $guests,
    'checkin' => $checkin,
    'checkout' => $checkout
]);

$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    unset($_SESSION['room_id']);
    header("Location: reservation.php?error=That room is no longer available.");
    exit;
}

$total_cost = (float)$room['price_per_night'] * $nights;

try {
    $stmt = $pdo->prepare("
        INSERT INTO reservations
        (
            customer_id,
            room_id,
            number_of_guests,
            check_in_date,
            check_out_date,
            total_nights,
            total_cost,
            reservation_status
        )
        VALUES
        (
            :customer_id,
            :room_id,
            :guests,
            :checkin,
            :checkout,
            :nights,
            :cost,
            'CONFIRMED'
        )
    ");

    $stmt->execute([
        'customer_id' => $customer_id,
        'room_id' => $room_id,
        'guests' => $guests,
        'checkin' => $checkin,
        'checkout' => $checkout,
        'nights' => $nights,
        'cost' => $total_cost
    ]);

    $_SESSION['reservation_id'] = $pdo->lastInsertId();

    unset($_SESSION['room_id']);
    unset($_SESSION['checkin']);
    unset($_SESSION['checkout']);
    unset($_SESSION['guests']);

    header("Location: reservation_success.php");
    exit;

} catch (PDOException $e) {
    header("Location: reservation_summary.php?error=Reservation failed. Please try again.");
    exit;
}