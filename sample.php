<?php
session_start();


$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$name = $logged_in ? ($_SESSION['name'] ?? null) : null;
$alerts = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? '';


unset($_SESSION['alerts']);
unset($_SESSION['active_form']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Philtech | Excellence in Education Since 2012</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="sample.css">
</head>

<body>
    <header>
        <a href="sample.php" class="logo">PHILTECH</a>
        <nav>
            <a href="sample.php">Home</a>
            <a href="about_sample.php">About</a>
            <a href="service_sample.php">Services</a>
            <a href="contact_sample.php">Contact</a>
        </nav>

        <div class="user-auth">
            <?php if ($logged_in && !empty($name)): ?>
            <div class="profile-box">
                <div class="avatar-circle"><?= strtoupper(substr($name, 0, 1)); ?></div>
                <div class="dropdown">
                    <a href="profile_card.php">My Account</a>
                    <a href="logout.php">Sign Out</a>
                </div>
            </div>
            <?php else: ?>
            <button type="button" class="login-btn-modal">Login</button>
            <?php endif; ?>
        </div>
    </header>

    <div class="hero-wrapper">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">✨ Since 2010 — Excellence in education</div>
                <h1>Welcome to Philtech<br>Empowering students with world-class education</h1>
                <p>Innovative learning experiences. Join us in shaping tomorrow's leaders through excellence in education and commitment to student success.</p>
                <div class="btn-group">
                    <a href="#" class="btn-primary">Get Started Today <i class='bx bx-right-arrow-alt'></i></a>
                    <a href="#" class="btn-outline">Learn More</a>
                </div>
                <div class="collab-row">
                    <i class='bx bxs-group'></i>
                    <span>👩‍🎓👨‍🎓📚 Students studying · collaborative learning</span>
                </div>
            </div>
            <div class="hero-image-card">
                <img class="enrollment-img" src="about.jpg"
                     alt="Enrollment Open - Humanities, Accountancy, Business, Social Sciences, ICT, Economics">
            </div>
        </div>
    </div>

    <div class="stats-section">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">10,000+</div>
                <div class="stat-label">Students Enrolled</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Expert Faculty</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Programs Offered</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">95%</div>
                <div class="stat-label">Placement Rate</div>
            </div>
        </div>
    </div>

    <div class="features-section">
        <h2 class="section-title">Excellence in Education</h2>
        <p class="section-subtitle">At Philtech, we're committed to providing exceptional educational experiences that prepare students for success in an ever-evolving world.</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎓</div>
                <h3>Quality Education</h3>
                <p>Comprehensive curriculum designed by industry experts to ensure the highest standards of learning.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👩‍🏫</div>
                <h3>Expert Faculty</h3>
                <p>Learn from experienced professors and industry professionals dedicated to your success.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Accredited Programs</h3>
                <p>Internationally recognized degrees and certifications that open doors worldwide.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <h3>Career Growth</h3>
                <p>Career services and placement assistance to help you achieve your professional goals.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✨</div>
                <h3>Personalized Learning</h3>
                <p>Tailored educational paths that adapt to your unique learning style and pace.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌐</div>
                <h3>Global Network</h3>
                <p>Join a diverse community of learners and alumni from around the world.</p>
            </div>
        </div>
    </div>

    <div class="cta-section">
        <h2>Ready to Start Your Journey?</h2>
        <p>Join thousands of students who have transformed their lives through education at Philtech.</p>
        <a href="#" class="btn-primary" style="background: #ffb347; color: #1a2c3e;">Apply Now <i class='bx bxs-paper-plane'></i></a>
    </div>

     <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h4>Philtech</h4>
                <p>Empowering students with quality education and innovative learning experiences. Building tomorrow's leaders through excellence in education.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <a href="sample.php">Home</a>
                <a href="about_sample.php">About Us</a>
                <a href="#">Enrollment</a>
                <a href="contact.php">Contact</a>
            </div>
            <div class="footer-col">
                <h4>Contact Info</h4>
                <p><i class='bx bx-map'></i> 📍 CRDM Building, Congressional Rd, Maderan, General Mariano Alvarez, 4117 Cavite</p>
                <p><i class='bx bx-phone'></i> 📞 09972240222</p>
                <p ><i class='bx bx-envelope'></i><a href="//www.facebook.com/philtechgma2013"> ✉️ philtechgma2013 </a></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Philtech Technological Institution. All rights reserved.</p>
        </div>
    </footer>

    <?php if (!empty($alerts)): ?>
    <div class="alert-box">
        <?php foreach ($alerts as $alert): ?>
        <div class="alert <?= $alert['type']; ?>">
            <i class='bx <?= $alert['type'] === 'success' ? 'bxs-check-circle' : 'bxs-x-circle' ?>'></i>
            <span><?= htmlspecialchars($alert['message']); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="auth-modal<?= $active_form === 'register' ? ' show slide' : ($active_form === 'login' ? ' show' : ''); ?>">
        <button type="button" class="close-btn-modal"><i class='bx bx-x'></i></button>

        <div class="form-box login">
            <h2>Login</h2>
            <form action="auth_process.php" method="POST">
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bx-lock'></i>
                </div>
                <button type="submit" name="login_btn" class="btn">Login</button>
                <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
            </form>
        </div>

        <div class="form-box register">
            <h2>Register</h2>
            <form action="auth_process.php" method="POST">
                <div class="input-box">
                    <input type="text" name="name" placeholder="Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock'></i>
                </div>
                <button type="submit" name="register_btn" class="btn">Register</button>
                <p>Already have an account? <a href="#" class="login-link">Login</a></p>
            </form>
        </div>
    </div>

    <script src="sample.js"></script>
</body>
</html>