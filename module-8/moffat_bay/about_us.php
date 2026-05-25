<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

session_start(); 

$errorMessage = '';
$successMessage = '';

$name = $email = $phone = $message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_inquiry'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['telephone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation logic
    if ($name === '' || $email === '' || $message === '') {
        $errorMessage = 'Please fill out all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address.';
    } else {
        // Successful submission logic
        $successMessage = "Thank you, " . htmlspecialchars($name) . "! Your inquiry has been sent.";
        
        $name = $email = $phone = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Moffat Bay Lodge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --bg: #e8dfd2;
            --card: #f4eee6;
            --nav: #3b2f2f;
            --accent: #d4a64a;
            --text: #2f2a26;
            --muted: #6b625b;
            --white: #ffffff;
            --border: #d7c9b6;
            --error: #8b1e1e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            background: url('images/image_3.jpg') no-repeat center center fixed;
            background-size: cover;
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text);
        }

        /* Header & Navigation */
        .lodge-header {
            background: rgba(59, 47, 47, 0.88);
            color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .main-nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        .main-nav a {
            text-decoration: none;
            color: var(--white);
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .main-nav a:hover {
            color: var(--accent);
        }

        .login-button {
            background-color: var(--accent);
            color: var(--white);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }

        /* Main Content */
        .about-wrapper {
            flex: 1;
            padding: 60px 20px;
            background: rgba(0, 0, 0, 0.2);
        }

        .hero-section {
            text-align: center;
            color: #fff;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero-section h1 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .content-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }

        .content-box, .contact-footer-box, .cta-box, .inquiry-form {
            background: rgba(255, 255, 255, 0.96);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }

        h2, h3 {
            font-family: Georgia, "Times New Roman", serif;
            color: var(--nav);
            margin-bottom: 15px;
        }

        .contact-footer-box, .inquiry-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .contact-details-row {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin-top: 20px;
        }

        .detail-item {
            flex: 1;
        }

        .detail-item strong {
            display: block;
            font-family: Georgia, serif;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .detail-item p, .detail-item a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
        }

        
        .contact-divider {
            border: 0;
            height: 1px;
            background: var(--border);
            margin: 30px auto;
            width: 80%;
        }

        .social-media-container {
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .social-item a {
            text-decoration: none;
            color: var(--text);
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s;
        }

        .social-item i {
            color: var(--accent);
            font-size: 1.5rem;
        }

        .social-item a:hover {
            color: var(--accent);
        }

        .cta-box {
            text-align: center;
        }

        .view-rooms-btn {
            display: inline-block;
            margin-top: 15px;
            background-color: var(--accent);
            color: var(--white);
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: filter 0.3s;
            border: none;
            cursor: pointer;
        }

        .view-rooms-btn:hover {
            filter: brightness(0.9);
        }

        /* Form Field Styling */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #312117;
            font-size: 13px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            font-family: inherit;
            background-color: #fff;
        }

        input:focus, textarea:focus {
            border-color: #c9a35e; 
            outline: none;
            box-shadow: 0 0 0 2px rgba(201, 163, 94, 0.2);
        }

        .error-message {
            background-color: #f8d7da;
            color: var(--error);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            font-weight: bold;
            text-align: center;
        }

        .success-alert {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        .lodge-footer {
            background: var(--nav);
            color: var(--white);
            padding: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        @media (max-width: 850px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .contact-details-row {
                flex-direction: column;
                gap: 30px;
            }
        }
    </style>
</head>

<body>
    <header class="lodge-header">
        <div class="logo">MOFFAT BAY LODGE</div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="activities.php">Activities</a></li>
                <li><a href="dining.php">Dining &amp; More</a></li>
                <li><a href="about_us.php">About Us</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <a href="login.php" class="login-button">Log In</a>
        </div>
    </header>

    <main class="about-wrapper">
        <section class="hero-section">
            <h1>About Us - Moffat Bay Lodge</h1>
            <p>Experience the perfect balance of relaxation and adventure on Joviedsa Island.</p>
        </section>

        <div class="content-container">
            <?php if ($errorMessage !== ''): ?>
                <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <?php if ($successMessage !== ''): ?>
                <div class="success-alert"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>

            <section class="info-grid">
                <div class="content-box">
                    <h2>Our Story</h2>
                    <p>Nestled along Joviedsa Island and surrounded by breathtaking natural beauty, Moffat Bay Lodge offers a peaceful retreat designed for comfort and connection with nature.</p>
                </div>
                <div class="content-box">
                    <h2>What We Offer</h2>
                    <p>From cozy rooms with bay-side views to outdoor adventures like hiking and scuba diving, every detail is crafted to provide a true "home away from home" experience.</p>
                </div>
            </section>

            <section class="contact-footer-box">
                <h2>Contact Us</h2>
                <div class="contact-details-row">
                    <div class="detail-item">
                        <strong>📍 Address</strong>
                        <p>456 Joviedsa Bay Rd, Joviedsa Island, WA 98272</p>
                    </div>
                    <div class="detail-item">
                        <strong>📞 Phone</strong>
                        <p>(555) 867-5309</p>
                    </div>
                    <div class="detail-item">
                        <strong>✉️ Email</strong>
                        <p><a href="mailto:stay@moffatbaylodge.com">stay@moffatbaylodge.com</a></p>
                    </div>
                </div>

                <hr class="contact-divider">

                <div class="social-media-container">
                    <div class="social-item">
                        <a href="https://www.instagram.com" target="_blank">
                            <i class="fab fa-instagram"></i> @moffatbaylodge
                        </a>
                    </div>
                    <div class="social-item">
                        <a href="https://www.facebook.com" target="_blank">
                            <i class="fab fa-facebook"></i> Moffat Bay Lodge
                        </a>
                    </div>
                </div>
            </section>

            <section class="inquiry-form">
                <h2>Send Us a Message</h2>
                
                <form action="about_us.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="John Doe" 
                               value="<?php echo htmlspecialchars($name ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="j.doe@example.com"
                               value="<?php echo htmlspecialchars($email ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="123-456-7890"
                               value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="How can we help?"><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="submit_inquiry" class="view-rooms-btn">Submit Inquiry</button>
                </form>
            </section>

            <section class="cta-box">
                <h3>Ready to Book Your Stay?</h3>
                <a href="index.php" class="view-rooms-btn">Check Availability</a>
            </section>
        </div>
    </main>

    <footer class="site-footer" style="background: var(--nav); color: var(--white); text-align: center; padding: 1.25rem;">
        <p>&copy; 2026 Moffat Bay Lodge | All Rights Reserved</p>
    </footer>
</body>
</html>