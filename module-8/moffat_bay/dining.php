<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

session_start();
require_once __DIR__ . '/auth.php';

$user = getLoggedInUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dining & More - Moffat Bay Lodge</title>

    <style>
        :root {
            --dark: #3b2f2f;
            --accent: #d4a64a;
            --cream: #f4eee6;
            --tan: #e8dfd2;
            --text: #2f2a26;
            --muted: #6b625b;
            --white: #ffffff;
            --border: #d7c9b6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--tan);
            color: var(--text);
            line-height: 1.6;
        }

        .nav {
            background: var(--dark);
            color: var(--white);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 1.4rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a,
        .login-btn {
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .login-btn {
            background: var(--accent);
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .hero {
            min-height: 500px;

            display: flex;
            align-items: center;      /* vertical center */
            justify-content: center;  /* horizontal center */

            text-align: center;       /* centers text inside */

            background:
                linear-gradient(rgba(35, 30, 25, 0.45), rgba(35, 30, 25, 0.55)),
                url("images/image_9.jpg");

            background-size: cover;
            background-position: center 75%;
            background-repeat: no-repeat;

            color: white;
            padding: 2rem;
        }

        .hero-content {
            max-width: 850px;
        }

        .hero h1 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(2.6rem, 6vw, 4.5rem);
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            max-width: 760px;
            margin: 0 auto;
        }

        .section {
            max-width: 1100px;
            margin: 0 auto;
            padding: 4rem 1.5rem;
        }

        .intro {
            text-align: center;
        }

        .intro h2 {
            font-family: Georgia, "Times New Roman", serif;
            color: var(--dark);
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .intro p {
            max-width: 820px;
            margin: 0 auto 1rem;
            color: var(--muted);
            font-size: 1.05rem;
        }

        .dining-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: stretch;
        }

        .card {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        }

        .card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
        }

        .card-content {
            padding: 1.75rem;
        }

        .card h3 {
            font-family: Georgia, "Times New Roman", serif;
            color: var(--dark);
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        .card p {
            color: var(--muted);
            margin-bottom: 1rem;
        }

        .card ul {
            padding-left: 1.3rem;
            color: var(--text);
            margin-bottom: 1.25rem;
        }

        .card li {
            margin-bottom: 0.45rem;
        }

        .details {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 1rem;
            margin-top: 1rem;
        }

        .details p {
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .details strong {
            color: var(--dark);
        }

        .cta {
            background: var(--dark);
            color: var(--white);
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .cta h2 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta p {
            max-width: 720px;
            margin: 0 auto;
        }

        footer {
            background: var(--dark);
            color: var(--white);
            text-align: center;
            padding: 1.25rem;
        }

        @media (max-width: 800px) {
            .nav {
                flex-direction: column;
                text-align: center;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .dining-grid {
                grid-template-columns: 1fr;
            }

            .card img {
                height: 220px;
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="nav">
        <div class="logo">MOFFAT BAY LODGE</div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="activities.php">Activities</a>
            <a href="dining.php">Dining &amp; More</a>
            <a href="about_us.php">About Us</a>
        </div>

        <div>
            <?php if ($user): ?>
                <a href="logout.php" class="login-btn">Log Out</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Log In</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Dining at Moffat Bay</h1>
        <p>
            Enjoy fresh, locally inspired meals at Moffat Bay Lodge while taking in
            beautiful waterfront views and the peaceful surroundings of the bay.
        </p>
    </div>
</section>

<main>
    <section class="section intro">
        <h2>Relax, Dine, and Enjoy the View</h2>
        <p>
            Whether you are sitting down for a full meal or grabbing something quick before heading out,
            our dining options are designed for both comfort and convenience.
        </p>
        <p>
            After your meal, relax and enjoy the peaceful surroundings of Moffat Bay, or continue your day
            exploring everything the lodge has to offer.
        </p>
    </section>

    <section class="section">
        <div class="dining-grid">

            <article class="card">
                <img src="images/image_7.jpg" alt="Rustic lodge dining room with lake view">

                <div class="card-content">
                    <h3>Lodge Dining Experience</h3>
                    <p>Our lodge dining area offers a warm, rustic atmosphere perfect for relaxing after a day outdoors.</p>

                    <ul>
                        <li>Freshly prepared lunch and dinner options</li>
                        <li>Locally sourced Pacific Northwest ingredients</li>
                        <li>Comfortable indoor seating with scenic bay views</li>
                        <li>A welcoming setting for families, couples, and groups</li>
                    </ul>

                    <div class="details">
                        <p><strong>Operating Dates:</strong> April 2026 through October 2026</p>
                        <p><strong>Reservations:</strong> No reservation required; first come, first served</p>
                        <p><strong>Dress Code:</strong> Casual</p>
                    </div>
                </div>
            </article>

            <article class="card">
                <img src="images/image_8.jpg" alt="Casual grab and go canteen dining area">

                <div class="card-content">
                    <h3>Grab &amp; Go Options</h3>
                    <p>
                        For guests on the move, the Moffat Bay Canteen is open 24/7 and offers quick,
                        convenient options before or after your daily adventure.
                    </p>

                    <ul>
                        <li>Sandwiches and wraps</li>
                        <li>Salads, fruit, and light snacks</li>
                        <li>Pastries, muffins, and baked goods</li>
                        <li>Hot and cold beverages</li>
                    </ul>

                    <div class="details">
                        <p><strong>Perfect For:</strong></p>
                        <p>Early morning excursions, outdoor adventures, and guests who prefer quick, convenient meals.</p>
                    </div>
                </div>
            </article>

        </div>
    </section>
</main>

<footer>
    <p>&copy; 2026 Moffat Bay Lodge | All Rights Reserved</p>
</footer>

</body>
</html>