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

if (!$user) {
    header("Location: login.php?message=Please log in to review and confirm your reservation.");
    exit;
}

$room_id = $_SESSION['room_id'] ?? null;
$checkin = $_SESSION['checkin'] ?? null;
$checkout = $_SESSION['checkout'] ?? null;
$guests = $_SESSION['guests'] ?? null;
$error = trim((string)($_GET['error'] ?? ''));

if (!$room_id || !$checkin || !$checkout || !$guests) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        r.room_id,
        r.room_number,
        rt.room_size,
        rt.price_per_night,
        rt.max_occupancy
    FROM rooms r
    JOIN room_types rt ON r.room_type_id = rt.room_type_id
    WHERE r.room_id = :room_id
    LIMIT 1
");

$stmt->execute(['room_id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    unset($_SESSION['room_id']);
    header("Location: reservation.php?error=Selected room was not found.");
    exit;
}

$days = (new DateTime($checkin))->diff(new DateTime($checkout))->days;

if ($days <= 0) {
    header("Location: index.php");
    exit;
}

$total = (float)$room['price_per_night'] * $days;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Summary - Moffat Bay Lodge</title>
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

        <h2>Reservation Summary</h2>
        <p>Please review your reservation before confirming.</p>

        <?php if ($error !== ''): ?>
            <p class="msg msg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <p><strong>Guest:</strong> <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
        <p><strong>Room:</strong> <?php echo htmlspecialchars($room['room_size']); ?> Room <?php echo htmlspecialchars((string)$room['room_number']); ?></p>
        <p><strong>Guests:</strong> <?php echo htmlspecialchars((string)$guests); ?></p>
        <p><strong>Check-in:</strong> <?php echo htmlspecialchars($checkin); ?></p>
        <p><strong>Check-out:</strong> <?php echo htmlspecialchars($checkout); ?></p>
        <p><strong>Total Nights:</strong> <?php echo htmlspecialchars((string)$days); ?></p>
        <p><strong>Price Per Night:</strong> $<?php echo number_format((float)$room['price_per_night'], 2); ?></p>

        <h3>Total Cost: $<?php echo number_format($total, 2); ?></h3>

        <form action="submit_reservation.php" method="POST">
            <button type="submit" class="register-button">Confirm Reservation</button>
        </form>

        <div class="login-link">
            <a href="index.php">Start Over</a>
        </div>

    </div>
</main>

<footer class="lodge-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All rights reserved.</p>
</footer>

</body>
</html>