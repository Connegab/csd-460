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

$email = trim($_POST['email'] ?? '');
$reservationId = trim($_POST['reservation_id'] ?? '');

$error = '';
$reservations = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($email === '' && $reservationId === '') {
        $error = 'Please enter an email address or reservation number.';
    } else {
        $sql = "
            SELECT 
                res.reservation_id,
                res.check_in_date,
                res.check_out_date,
                res.number_of_guests,
                res.total_nights,
                res.total_cost,
                res.reservation_status,
                c.first_name,
                c.last_name,
                c.email,
                rm.room_number,
                rt.room_size
            FROM reservations res
            JOIN customers c ON res.customer_id = c.customer_id
            JOIN rooms rm ON res.room_id = rm.room_id
            JOIN room_types rt ON rm.room_type_id = rt.room_type_id
            WHERE 1 = 1
        ";

        $params = [];

        if ($email !== '') {
            $sql .= " AND c.email = :email";
            $params['email'] = $email;
        }

        if ($reservationId !== '') {
            $sql .= " AND res.reservation_id = :reservation_id";
            $params['reservation_id'] = $reservationId;
        }

        $sql .= " ORDER BY res.check_in_date DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$reservations) {
            $error = 'No reservation found matching the information provided.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lookup Reservation - Moffat Bay Lodge</title>
    <link rel="stylesheet" href="registration.css">
</head>
<body>

<header class="lodge-header">
    <div class="logo">MOFFAT BAY LODGE</div>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Activities</a></li>
            <li><a href="dining.php">Dining &amp; More</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="lookup_reservation.php">Lookup Reservation</a></li>
        </ul>
    </nav>

    <div class="header-right">
        <?php if ($user): ?>
            <a href="logout.php" class="login-button">Log Out</a>
        <?php else: ?>
            <a href="login.php" class="login-button">Log In</a>
        <?php endif; ?>
    </div>
</header>

<main class="registration-wrapper">
    <div class="registration-form-box">

        <h2>Lookup Reservation</h2>
        <p>Enter your email address or reservation number to view your reservation details.</p>

        <?php if ($error !== ''): ?>
            <p class="msg msg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="lookup_reservation.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($email); ?>" 
                    placeholder="example@email.com"
                >
            </div>

            <div class="form-group">
                <label for="reservation_id">Reservation Number</label>
                <input 
                    type="text" 
                    id="reservation_id" 
                    name="reservation_id" 
                    value="<?php echo htmlspecialchars($reservationId); ?>" 
                    placeholder="Example: 13"
                >
            </div>

            <button type="submit" class="register-button">Search Reservation</button>
        </form>

        <?php if (!empty($reservations)): ?>
            <hr style="margin: 25px 0;">

            <?php foreach ($reservations as $reservation): ?>
                <div class="msg msg-success" style="text-align:left;">
                    <strong>Reservation Number:</strong> 
                    <?php echo htmlspecialchars((string)$reservation['reservation_id']); ?><br>

                    <strong>Guest:</strong> 
                    <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?><br>

                    <strong>Email:</strong> 
                    <?php echo htmlspecialchars($reservation['email']); ?><br>

                    <strong>Room:</strong> 
                    <?php echo htmlspecialchars($reservation['room_size']); ?> Room 
                    <?php echo htmlspecialchars((string)$reservation['room_number']); ?><br>

                    <strong>Guests:</strong> 
                    <?php echo htmlspecialchars((string)$reservation['number_of_guests']); ?><br>

                    <strong>Check-in:</strong> 
                    <?php echo htmlspecialchars($reservation['check_in_date']); ?><br>

                    <strong>Check-out:</strong> 
                    <?php echo htmlspecialchars($reservation['check_out_date']); ?><br>

                    <strong>Total Nights:</strong> 
                    <?php echo htmlspecialchars((string)$reservation['total_nights']); ?><br>

                    <strong>Total Cost:</strong> 
                    $<?php echo number_format((float)$reservation['total_cost'], 2); ?><br>

                    <strong>Status:</strong> 
                    <?php echo htmlspecialchars($reservation['reservation_status']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

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