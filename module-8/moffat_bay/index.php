<?php
/**
 * Team Charlie
 * Team Members: Paul Fralix, James Brown III, Gabriel Conner, Alexis Mitchell, Hlee Xiong
 * Moffat Bay Lodge
 * CSD460 Capstone in Software Development
 */

session_start();

$errorMessage = '';
$allowedGuests = [2, 3, 4, 5];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkIn = trim($_POST['checkin'] ?? '');
    $checkOut = trim($_POST['checkout'] ?? '');
    $guests = trim($_POST['guests'] ?? '');

    if ($checkIn === '' || $checkOut === '' || $guests === '') {
        $errorMessage = 'All fields are required.';
    } else {
        $checkInDate = DateTime::createFromFormat('Y-m-d', $checkIn);
        $checkOutDate = DateTime::createFromFormat('Y-m-d', $checkOut);

        $checkInValid = $checkInDate && $checkInDate->format('Y-m-d') === $checkIn;
        $checkOutValid = $checkOutDate && $checkOutDate->format('Y-m-d') === $checkOut;

        if (!$checkInValid || !$checkOutValid) {
            $errorMessage = 'Please enter valid dates.';
        } elseif ($checkOutDate <= $checkInDate) {
            $errorMessage = 'Check-out date must be after check-in date.';
        } elseif (!ctype_digit($guests) || !in_array((int)$guests, $allowedGuests, true)) {
            $errorMessage = 'Please select a valid number of guests.';
        } else {
            $_SESSION['checkin'] = $checkIn;
            $_SESSION['checkout'] = $checkOut;
            $_SESSION['guests'] = (int)$guests;

            // Clear any old room selection when a new search is made
            unset($_SESSION['room_id']);

            header('Location: reservation.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Moffat Bay Lodge</title>
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
      background: var(--bg);
      color: var(--text);
      line-height: 1.5;
    }

    .hero {
      height: 75vh;
      display: flex;
      flex-direction: column;
      background:
        linear-gradient(rgba(35, 30, 25, 0.35), rgba(35, 30, 25, 0.45)),
        url("images/image_1.jpg") center 10% / cover no-repeat;
    }

    .nav {
      background: rgba(59, 47, 47, 0.88);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .logo {
      color: var(--white);
      font-family: Georgia, "Times New Roman", serif;
      font-size: 1.4rem;
      font-weight: bold;
      letter-spacing: 1px;
      white-space: nowrap;
      min-width: 260px;
    }

    .nav-center {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 4rem;
      flex: 1;
    }

    .nav-center a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      white-space: nowrap;
    }

    .nav-center a:hover {
      color: #d4a64a;
    }

    .nav-right {
      display: flex;
      justify-content: flex-end;
      min-width: 120px;
    }

    .login-btn {
      background: #d4a64a;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      white-space: nowrap;
    }

    .login-btn:hover {
      background: #c3953d;
    }

    .hero-content {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.5rem 2rem;
    }

    .hero-inner {
      width: 100%;
      max-width: 1100px;
      text-align: center;
      color: var(--white);
    }

    .hero h1 {
      font-family: Georgia, "Times New Roman", serif;
      font-size: clamp(2.4rem, 6vw, 4.75rem);
      margin-bottom: 1rem;
    }

    .hero p {
      max-width: 700px;
      margin: 0 auto 2rem;
      font-size: 1.05rem;
    }

    .error-message {
      max-width: 900px;
      margin: 0 auto 1rem;
      background: rgba(255, 240, 240, 0.95);
      color: var(--error);
      border: 1px solid #d9a3a3;
      border-radius: 12px;
      padding: 0.85rem 1rem;
      font-weight: 700;
      text-align: center;
    }

    .booking-bar {
      max-width: 900px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.96);
      border-radius: 16px;
      padding: 1rem;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0.75rem;
      color: var(--text);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
    }

    .booking-field {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 0.9rem 1rem;
      text-align: left;
    }

    .booking-field label {
      display: block;
      font-size: 0.85rem;
      color: var(--muted);
      margin-bottom: 0.35rem;
      font-weight: 700;
    }

    .booking-field input,
    .booking-field select {
      width: 100%;
      border: none;
      background: transparent;
      font-size: 1rem;
      color: var(--text);
      outline: none;
    }

    .booking-button {
      border: none;
      border-radius: 12px;
      background: var(--accent);
      color: var(--white);
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      padding: 0 1rem;
      min-height: 100%;
    }

    .booking-button:hover {
      filter: brightness(0.95);
    }

    .section {
      max-width: 1100px;
      margin: 0 auto;
      padding: 4rem 1.5rem;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.25rem;
      margin-top: -2.5rem;
      position: relative;
      z-index: 2;
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 1.5rem;
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .card h3 {
      font-family: Georgia, "Times New Roman", serif;
      margin-bottom: 0.75rem;
      color: var(--nav);
    }

    .card p {
      color: var(--muted);
    }

    .logo-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 1.5rem;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        
        width: 100%;
        max-width: 250px; 
        height: 180px;
        margin: 0 auto 2.5rem auto;
        
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .discover-logo {
        max-width: 85% !important; 
        max-height: 85% !important;
        width: auto !important;
        height: auto !important;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
    }

    .discover {
        text-align: center;
        padding-top: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .discover h2 {
      font-family: Georgia, "Times New Roman", serif;
      font-size: 2.2rem;
      color: var(--nav);
      margin-bottom: 1rem;
    }

    .discover p {
      max-width: 720px;
      margin: 0 auto 1.75rem;
      color: var(--muted);
      font-size: 1.05rem;
    }

    .contact-link {
      display: inline-block;
      color: var(--nav);
      font-weight: 700;
      text-decoration: none;
      border-bottom: 2px solid var(--accent);
      padding-bottom: 0.2rem;
      margin-bottom: 3rem;
    }

    .bottom-bar {
      height: 80px;
      background: linear-gradient(to top, #2f2525, #3b2f2f);
      box-shadow: 0 -10px 25px rgba(0,0,0,0.3);
      margin-top: 3rem;
    }

    footer {
      background: var(--nav);
      color: var(--white);
      text-align: center;
      padding: 1.25rem;
    }
    
    @media (max-width: 900px) {
      .booking-bar {
        grid-template-columns: 1fr 1fr;
      }

      .cards {
        grid-template-columns: 1fr;
        margin-top: 2rem;
      }

      .nav {
        flex-wrap: wrap;
      }

      .logo,
      .nav-right {
        min-width: auto;
      }

      .nav-center {
        gap: 2rem;
        flex-wrap: wrap;
      }
    }

    @media (max-width: 560px) {
      .booking-bar {
        grid-template-columns: 1fr;
      }

      .nav {
        justify-content: center;
        text-align: center;
      }

      .nav-center {
        justify-content: center;
        gap: 1rem;
        width: 100%;
        flex-wrap: wrap;
      }

      .nav-right {
        width: 100%;
        justify-content: center;
      }

      .logo {
        width: 100%;
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <header class="hero">
    <nav class="nav">
      <div class="logo">MOFFAT BAY LODGE</div>

      <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="activities.php">Activities</a>
        <a href="dining.php">Dining &amp; More</a>
        <a href="about_us.php">About Us</a>
      </div>

      <div class="nav-right">
        <a href="login.php" class="login-btn">Log In</a>
      </div>
    </nav>

    <div class="hero-content">
      <div class="hero-inner">
        <h1>Your Getaway Awaits</h1>
        <p>Book a relaxing stay at Moffat Bay Lodge and enjoy beautiful scenery, outdoor adventure, and a cozy waterfront experience.</p>

        <?php if ($errorMessage !== ''): ?>
          <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form class="booking-bar" method="post" action="index.php">
          <div class="booking-field">
            <label for="checkin">Check-in</label>
            <input
              id="checkin"
              name="checkin"
              type="date"
              value="<?php echo htmlspecialchars($_POST['checkin'] ?? ''); ?>"
              required
            >
          </div>

          <div class="booking-field">
            <label for="checkout">Check-out</label>
            <input
              id="checkout"
              name="checkout"
              type="date"
              value="<?php echo htmlspecialchars($_POST['checkout'] ?? ''); ?>"
              required
            >
          </div>

          <div class="booking-field">
            <label for="guests">Guests</label>
            <select id="guests" name="guests" required>
              <option value="2" <?php echo (($_POST['guests'] ?? '2') === '2') ? 'selected' : ''; ?>>2 Guests</option>
              <option value="3" <?php echo (($_POST['guests'] ?? '') === '3') ? 'selected' : ''; ?>>3 Guests</option>
              <option value="4" <?php echo (($_POST['guests'] ?? '') === '4') ? 'selected' : ''; ?>>4 Guests</option>
              <option value="5" <?php echo (($_POST['guests'] ?? '') === '5') ? 'selected' : ''; ?>>5 Guests</option>
            </select>
          </div>

          <button class="booking-button" type="submit">Check Availability</button>
        </form>
      </div>
    </div>
  </header>

  <main>
    <section class="section">
      <div class="cards">
        <article class="card">
          <h3>Cozy Rooms</h3>
          <p>Comfortable lodge rooms with stunning views and relaxing accommodations for your stay.</p>
        </article>

        <article class="card">
          <h3>Outdoor Activities</h3>
          <p>Hiking, kayaking, whale watching, scuba diving, and more adventure around the island.</p>
        </article>

        <article class="card">
          <h3>Dining &amp; More</h3>
          <p>Delicious local cuisine, refreshing drinks, and convenient grab-and-go options.</p>
        </article>
      </div>
    </section>

    <section class="section discover">
      <div class="content-container">
          <div class="card logo-card">
              <img src="images/image_10.png" alt="Moffat Bay Salmon" class="discover-logo">
          </div>

          <h2>Discover Moffat Bay</h2>
          <p>
              Experience the beauty and adventure of Moffat Bay Lodge. Perfect for a romantic escape
              or a family vacation.
          </p>
          <a class="contact-link" href="gallery.php">View Gallery</a>
      </div>
  </section>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 Moffat Bay Lodge | All Rights Reserved</p>
  </footer>
</body>
</html>