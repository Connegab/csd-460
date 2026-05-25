<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function redirectAfterLogin(): void
{
    if (
        isset($_SESSION['checkin'], $_SESSION['checkout'], $_SESSION['guests'], $_SESSION['room_id']) &&
        $_SESSION['checkin'] !== '' &&
        $_SESSION['checkout'] !== '' &&
        $_SESSION['guests'] !== '' &&
        $_SESSION['room_id'] !== ''
    ) {
        header('Location: reservation_summary.php');
    } elseif (
        isset($_SESSION['checkin'], $_SESSION['checkout'], $_SESSION['guests']) &&
        $_SESSION['checkin'] !== '' &&
        $_SESSION['checkout'] !== '' &&
        $_SESSION['guests'] !== ''
    ) {
        header('Location: reservation.php');
    } else {
        header('Location: dashboard.php');
    }
    exit;
}

if (getLoggedInUser() !== null) {
    redirectAfterLogin();
}

$errorMessage = '';
$successMessage = trim((string) ($_GET['success'] ?? ''));
$infoMessage = trim((string) ($_GET['message'] ?? ''));
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $errorMessage = 'Please enter both your email address and password.';
    } else {
        try {
            $user = authenticateUser($email, $password);

            if ($user === null) {
                $errorMessage = 'We could not sign you in with that email and password.';
            } else {
                logUserIn($user);
                redirectAfterLogin();
            }
        } catch (PDOException $exception) {
            $errorMessage = 'Database connection failed. Check your db.php settings.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moffat Bay Lodge | Log In</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
<header class="site-header">
    <a class="site-title" href="index.php">Moffat Bay Lodge</a>
    <nav class="nav" aria-label="Primary navigation">
        <a href="index.php">Home</a>
        <a href="#">Activities</a>
        <a href="#">Dining &amp; More</a>
        <a href="about_us.php">About Us</a>
    </nav>
    <a class="header-button" href="login.php">Log In</a>
</header>

<main>
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-kicker">Welcome to</p>
            <h1 id="login-title">Moffat Bay Lodge</h1>
            <p class="subtitle">Sign in to manage your stay, review upcoming reservations, and keep planning your waterfront getaway.</p>

            <section class="login-card" aria-labelledby="login-title">
                <?php if ($successMessage !== ''): ?>
                    <div class="form-message" style="background:#d4edda;color:#155724;border:1px solid #b7dfc1;">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>

                <?php if ($infoMessage !== ''): ?>
                    <div class="form-message" style="background:#eef3ff;color:#1f3a6d;border:1px solid #c7d5f3;">
                        <?php echo htmlspecialchars($infoMessage); ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage !== ''): ?>
                    <div class="form-message error-message">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="field">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" placeholder="you@example.com"
                               value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Enter your password" required>
                    </div>

                    <button class="login-button" type="submit">Log In</button>
                </form>

                <p class="signup">Don’t have an account? <a href="registration.php">Create one here</a></p>
            </section>
        </div>
    </section>
</main>

<footer class="site-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All Rights Reserved</p>
</footer>
</body>
</html>