<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

session_start();

$checkin = $_SESSION['checkin'] ?? null;
$checkout = $_SESSION['checkout'] ?? null;
$guests = $_SESSION['guests'] ?? null;
$message = trim((string)($_GET['message'] ?? ''));
$error = trim((string)($_GET['error'] ?? ''));
$success = trim((string)($_GET['success'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Moffat Bay Lodge</title>
    <link rel="stylesheet" href="registration.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Lora:wght@500;700&display=swap" rel="stylesheet">
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
        <a href="login.php" class="login-button">Log In</a>
    </div>
</header>

<main class="registration-wrapper">
    <div class="registration-form-box">
        
        <h2>Create Your Account</h2>
        <p>Join us to manage your bookings and enjoy exclusive offers.</p>

        <?php if ($checkin && $checkout && $guests): ?>
            <div class="msg msg-success">
                Booking details saved:
                Check-in <?php echo htmlspecialchars($checkin); ?>,
                Check-out <?php echo htmlspecialchars($checkout); ?>,
                Guests <?php echo htmlspecialchars((string)$guests); ?>
            </div>
        <?php endif; ?>

        <?php if ($message !== ''): ?>
            <p class="msg msg-success"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <p class="msg msg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <p class="msg msg-success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="process_registration.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required placeholder="John">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required placeholder="Doe">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="j.doe@example.com">
            </div>

            <div class="form-group">
                <label for="telephone">Telephone (Optional)</label>
                <input type="tel" id="telephone" name="telephone" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="8" placeholder="At least 8 characters">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirm Password</label>
                <input type="password" id="password_confirm" name="password_confirm" required placeholder="Repeat your password">
            </div>

            <button type="submit" class="register-button">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Log In here</a>
        </div>
    </div>
</main>

<footer class="lodge-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All rights reserved.</p>
</footer>

</body>
</html>