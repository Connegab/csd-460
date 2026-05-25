<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

session_start();

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

$user = getLoggedInUser();

$checkin = $_SESSION['checkin'] ?? '';
$checkout = $_SESSION['checkout'] ?? '';
$guests = (int)($_SESSION['guests'] ?? 0);

$error = trim((string)($_GET['error'] ?? ''));
$availableRooms = [];

if ($checkin === '' || $checkout === '' || $guests <= 0) {
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            r.room_id,
            r.room_number,
            rt.room_size,
            rt.price_per_night,
            rt.max_occupancy
        FROM rooms r
        JOIN room_types rt ON r.room_type_id = rt.room_type_id
        WHERE rt.max_occupancy >= :guests
          AND r.room_id NOT IN (
              SELECT room_id
              FROM reservations
              WHERE check_in_date < :checkout
                AND check_out_date > :checkin
          )
        ORDER BY rt.room_size, r.room_number
    ");

    $stmt->execute([
        'guests' => $guests,
        'checkin' => $checkin,
        'checkout' => $checkout
    ]);

    $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = "Error loading rooms.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = (int)($_POST['room_id'] ?? 0);

    if ($roomId > 0) {
        $_SESSION['room_id'] = $roomId;

        if ($user === null) {
            header("Location: registration.php?message=Please register or log in to continue your booking.");
        } else {
            header("Location: reservation_summary.php");
        }
        exit;
    } else {
        $error = "Please select a room.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - Moffat Bay Lodge</title>
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
        <?php if ($user): ?>
            <a href="logout.php" class="login-button">Log Out</a>
        <?php else: ?>
            <a href="login.php" class="login-button">Log In</a>
        <?php endif; ?>
    </div>
</header>

<main class="registration-wrapper">
    <div class="registration-form-box">

        <h2>Available Rooms</h2>
        <p>Select a room to continue your reservation.</p>

        <div class="msg msg-success">
            Check-in: <?php echo htmlspecialchars($checkin); ?><br>
            Check-out: <?php echo htmlspecialchars($checkout); ?><br>
            Guests: <?php echo htmlspecialchars((string)$guests); ?>
        </div>

        <?php if ($error !== ''): ?>
            <p class="msg msg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (empty($availableRooms)): ?>
            <p class="msg msg-error">No rooms are available for the selected dates and guest count.</p>

            <form action="index.php" method="get">
                <button type="submit" class="register-button">Start Over</button>
            </form>

        <?php else: ?>

            <form method="POST">
                <div class="form-group">
                    <label for="room_id">Select Your Room</label>
                    <select id="room_id" name="room_id" required>
                        <option value="">Select a Room</option>

                        <?php foreach ($availableRooms as $room): ?>
                            <option value="<?php echo htmlspecialchars((string)$room['room_id']); ?>">
                                Room <?php echo htmlspecialchars((string)$room['room_number']); ?> |
                                <?php echo htmlspecialchars($room['room_size']); ?> |
                                $<?php echo number_format((float)$room['price_per_night'], 2); ?>/night |
                                Max <?php echo htmlspecialchars((string)$room['max_occupancy']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="register-button">Continue</button>
            </form>

            <div class="login-link">
                <a href="index.php">Change dates or guests</a>
            </div>

        <?php endif; ?>

    </div>
</main>

<footer class="lodge-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All rights reserved.</p>
</footer>

</body>
</html>