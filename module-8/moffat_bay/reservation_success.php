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
$reservation_id = $_SESSION['reservation_id'] ?? null;

if (!$user || !$reservation_id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        res.reservation_id,
        res.check_in_date,
        res.check_out_date,
        res.number_of_guests,
        res.total_nights,
        res.total_cost,
        res.reservation_status,
        rm.room_number,
        rt.room_size
    FROM reservations res
    JOIN rooms rm ON res.room_id = rm.room_id
    JOIN room_types rt ON rm.room_type_id = rt.room_type_id
    WHERE res.reservation_id = :reservation_id
      AND res.customer_id = :customer_id
    LIMIT 1
");

$stmt->execute([
    'reservation_id' => $reservation_id,
    'customer_id' => $user['customer_id']
]);

$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header("Location: index.php");
    exit;
}

unset($_SESSION['reservation_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed - Moffat Bay Lodge</title>
    <link rel="stylesheet" href="registration.css">
</head>
<body>

<header class="lodge-header">
    <div class="logo">MOFFAT BAY LODGE</div>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Activities</a></li>
            <li><a href="#">Dining &amp; More</a></li>
            <li><a href="about_us.php">About Us</a></li>
        </ul>
    </nav>

    <div class="header-right">
        <a href="logout.php" class="login-button">Log Out</a>
    </div>
</header>

<main class="registration-wrapper">
    <div class="registration-form-box">

        <h2>Reservation Confirmed!</h2>
        <p>Your booking has been successfully completed.</p>

        <div class="msg msg-success">
            Confirmation Number: <?php echo htmlspecialchars((string)$reservation['reservation_id']); ?>
        </div>

        <p><strong>Room:</strong> <?php echo htmlspecialchars($reservation['room_size']); ?> Room <?php echo htmlspecialchars((string)$reservation['room_number']); ?></p>
        <p><strong>Guests:</strong> <?php echo htmlspecialchars((string)$reservation['number_of_guests']); ?></p>
        <p><strong>Check-in:</strong> <?php echo htmlspecialchars($reservation['check_in_date']); ?></p>
        <p><strong>Check-out:</strong> <?php echo htmlspecialchars($reservation['check_out_date']); ?></p>
        <p><strong>Total Nights:</strong> <?php echo htmlspecialchars((string)$reservation['total_nights']); ?></p>
        <p><strong>Total Cost:</strong> $<?php echo number_format((float)$reservation['total_cost'], 2); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($reservation['reservation_status']); ?></p>

        <div class="login-link">
            <a href="index.php">Return Home</a>
        </div>

    </div>
</main>

<footer class="lodge-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All rights reserved.</p>
</footer>

</body>
</html>