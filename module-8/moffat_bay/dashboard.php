<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

requireLogin();

$user = getLoggedInUser();
$displayName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

if ($displayName === '') {
    $displayName = $user['email'] ?? 'Guest';
}

$reservations = [];

try {
    $stmt = $pdo->prepare("
        SELECT reservation_id, check_in_date, check_out_date
        FROM reservations
        WHERE customer_id = :customer_id
        AND reservation_status = 'CONFIRMED'
        ORDER BY reservation_id DESC
    ");

    $stmt->execute(['customer_id' => $user['customer_id']]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $reservations = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moffat Bay Lodge | Dashboard</title>
    <link rel="stylesheet" href="registration.css">
</head>

<body>

<header class="lodge-header">
    <div class="logo">MOFFAT BAY LODGE</div>

    <nav class="main-nav">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="lookup_reservation.php">Reservations</a></li>
            <li><a href="#">Activities</a></li>
            <li><a href="#">Dining &amp; More</a></li>
            <li><a href="#">Support</a></li>
        </ul>
    </nav>

    <div class="header-right">
        <a href="logout.php" class="login-button">Log Out</a>
    </div>
</header>

<main class="registration-wrapper">
    <div class="registration-form-box">

        <h2>Welcome, <?php echo htmlspecialchars($displayName); ?></h2>
        <p>Manage your reservations and explore what Moffat Bay Lodge has to offer.</p>

        <div class="msg msg-success">
            Email: <?php echo htmlspecialchars((string)$user['email']); ?><br>
            Customer ID: <?php echo htmlspecialchars((string)$user['customer_id']); ?>
        </div>

        <!-- RESERVATIONS SECTION -->
        <div style="margin-top:20px;">

            <h3>Your Confirmed Reservations</h3>

            <?php if (!empty($reservations)): ?>

                <?php foreach ($reservations as $res): ?>
                    <div class="msg msg-success" style="margin-bottom:10px;">
                        Reservation #: <?php echo $res['reservation_id']; ?><br>
                        Check-in: <?php echo $res['check_in_date']; ?><br>
                        Check-out: <?php echo $res['check_out_date']; ?>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <p>No confirmed reservations found.</p>
            <?php endif; ?>

        </div>

        <div style="margin-top:20px;">
            <a href="reservation.php" class="register-button" style="display:inline-block; text-decoration:none;">
                Make a Reservation
            </a>
        </div>

        <div style="margin-top:10px;">
            <a href="lookup_reservation.php" class="register-button" style="display:inline-block; text-decoration:none;">
                View Reservations
            </a>
        </div>

    </div>
</main>

<footer class="lodge-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All rights reserved.</p>
</footer>

</body>
</html>