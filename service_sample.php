<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_btn'])) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
       
        if ($email === 'student@philtech.edu' && $password === 'demo123') {
            $_SESSION['logged_in'] = true;
            $_SESSION['name'] = 'Alex Johnson';
            $_SESSION['email'] = $email;
            $success_msg = 'Welcome back! You are now logged in.';
        } else {
            $error_msg = 'Invalid email or password. Try student@philtech.edu / demo123';
        }
    } elseif (isset($_POST['register_btn'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name && $email && $password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $success_msg = 'Registration successful! Welcome to Philtech.';
        } else {
            $error_msg = 'Please fill all fields.';
        }
    }
    
    if ($success_msg) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => $success_msg];
    } elseif ($error_msg) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => $error_msg];
    }
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$user_name = $logged_in ? ($_SESSION['name'] ?? 'User') : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <link rel="stylesheet" href="service_sample.css">
    <title>Philtech | Professional Programs – SHS & College</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      
        .program-category .big-apply {
            margin-top: 56px;
            text-align: center;
        }
        
        .btn-apply-big {
            background: linear-gradient(105deg, #ffb347, #ff9f2e, #ffb347);
            background-size: 200% auto;
            color: #1a2c3e;
            padding: 18px 56px;
            border-radius: 60px;
            font-weight: 800;
            font-size: 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            transition: all 0.4s ease;
            box-shadow: 0 15px 30px -8px rgba(255, 179, 71, 0.5);
            border: none;
            cursor: pointer;
            letter-spacing: 0.8px;
            position: relative;
            overflow: hidden;
        }
        .btn-apply-big::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s ease;
        }
        .btn-apply-big:hover::before {
            left: 100%;
        }
        .btn-apply-big:hover {
            background-position: right center;
            transform: scale(1.03) translateY(-3px);
            box-shadow: 0 22px 40px -12px rgba(255, 179, 71, 0.7);
            color: #0f1f2c;
        }
        .btn-apply-big i {
            font-size: 26px;
            transition: transform 0.2s;
        }
        .btn-apply-big:hover i {
            transform: translateX(6px);
        }
        @media (max-width: 768px) {
            .btn-apply-big {
                padding: 14px 32px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="sample.php" class="logo">PHILTECH</a>
    <nav>
        <a href="sample.php">Home</a>
        <a href="about_sample.php">About</a>
        <a href="services_sample.php">Services</a>
        <a href="contact_sample.php">Contact</a>
    </nav>
    <div class="user-auth">
        <?php if ($logged_in): ?>
        <div class="profile-box">
            <div class="avatar-circle"><?= strtoupper(substr($user_name, 0, 1)) ?></div>
            <div class="dropdown">
                <a href="profile_card.php">My Account</a>
                <a href="?logout=1">Sign Out</a>
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
            <div class="hero-badge">✨ Since 2012 — Excellence in education</div>
            <h1>Welcome to Philtech<br>Empowering students with world-class education</h1>
            <p>Innovative learning experiences. Join us in shaping tomorrow's leaders through excellence in education and commitment to student success.</p>
            <div class="btn-group">
                <a href="#programsSection" class="btn-primary">Explore Programs <i class='bx bx-right-arrow-alt'></i></a>
                <a href="#" class="btn-outline">Learn More</a>
            </div>
            <div class="collab-row">
                <i class='bx bxs-group'></i>
                <span>👩‍🎓👨‍🎓📚 SHS · College · Future leaders</span>
            </div>
        </div>
        <div class="hero-image-card">
            <img class="enrollment-img" src="PHOTO ENROLL.jpg" alt="Enrollment">
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="stats-container">
        <div class="stat-item"><div class="stat-number">10,000+</div><div class="stat-label">Students Enrolled</div></div>
        <div class="stat-item"><div class="stat-number">50+</div><div class="stat-label">Expert Faculty</div></div>
        <div class="stat-item"><div class="stat-number">10+</div><div class="stat-label">Programs Offered</div></div>
        <div class="stat-item"><div class="stat-number">95%</div><div class="stat-label">Placement Rate</div></div>
    </div>
</div>


<div class="programs-showcase" id="programsSection">
    <div class="programs-grid">
        <!-- SHS Program Category -->
        <div class="program-category">
            <h2 class="category-title"><i class='bx bxs-graduation'></i> Senior High School Program</h2>
            <div class="program-list">
                <div class="program-card">
                    <div class="program-name"><i class='bx bxs-briefcase'></i> ABM</div>
                    <ul class="subjects-list">
                        <li><i class='bx bxs-book'></i> Fundamentals of Accountancy</li>
                        <li><i class='bx bxs-chart'></i> Business Math & Management</li>
                        <li><i class='bx bxs-bank'></i> Organization & Management</li>
                        <li><i class='bx bx-calculator'></i> Business Finance</li>
                    </ul>
                </div>
                <div class="program-card">
                    <div class="program-name"><i class='bx bxs-laptop'></i> ICT</div>
                    <ul class="subjects-list">
                        <li><i class='bx bx-code-alt'></i> Programming (Java/C++)</li>
                        <li><i class='bx bx-network-chart'></i> Computer Networking</li>
                        <li><i class='bx bx-data'></i> Database Management</li>
                        <li><i class='bx bx-shield'></i> Web Development & UI/UX</li>
                    </ul>
                </div>
                <div class="program-card">
                    <div class="program-name"><i class='bx bxs-brain'></i> HUMSS</div>
                    <ul class="subjects-list">
                        <li><i class='bx bx-pen'></i> Creative Writing</li>
                        <li><i class='bx bx-globe'></i> World Religions & Beliefs</li>
                        <li><i class='bx bx-landscape'></i> Philippine Politics & Governance</li>
                        <li><i class='bx bx-heart'></i> Community Engagement</li>
                    </ul>
                </div>
            </div>
            <!-- Single apply button for SHS -->
            <div class="big-apply">
                <button class="btn-apply-big login-btn-modal"><a href="shs_apply.php">🎓 ENROLL IN SHS PROGRAM</a> <i class='bx bxs-right-arrow'></i></button>
            </div>
        </div>

        <!-- College Program Category -->
        <div class="program-category">
            <h2 class="category-title"><i class='bx bxs-building'></i> College Program</h2>
            <div class="program-list">
                <div class="program-card">
                    <div class="program-name"><i class='bx bx-code-curly'></i> BSCS</div>
                    <ul class="subjects-list">
                        <li><i class='bx bx-cog'></i> Data Structures & Algorithms</li>
                        <li><i class='bx bx-cloud'></i> AI & Machine Learning</li>
                        <li><i class='bx bx-mobile-alt'></i> Mobile App Development</li>
                        <li><i class='bx bx-lock-open'></i> Cybersecurity Fundamentals</li>
                    </ul>
                </div>
                <div class="program-card">
                    <div class="program-name"><i class='bx bxs-chalkboard'></i> BTVETED</div>
                    <ul class="subjects-list">
                        <li><i class='bx bx-book-open'></i> Tech-Voc Pedagogy</li>
                        <li><i class='bx bx-wrench'></i> Industrial Arts & Design</li>
                        <li><i class='bx bx-hdd'></i> ICT Integration in Teaching</li>
                        <li><i class='bx bxs-school'></i> Assessment & Curriculum Dev</li>
                    </ul>
                </div>
                <div class="program-card">
                    <div class="program-name"><i class='bx bxs-folder-open'></i> BSOA</div>
                    <ul class="subjects-list">
                        <li><i class='bx bx-file'></i> Office Management & Procedures</li>
                        <li><i class='bx bx-stats'></i> Administrative Communications</li>
                        <li><i class='bx bx-time'></i> Records & HR Management</li>
                        <li><i class='bx bx-calendar'></i> Event Coordination</li>
                    </ul>
                </div>
            </div>
            <!-- Single apply button for College -->
            <div class="big-apply">
                <button class="btn-apply-big login-btn-modal"><a href="college_apply.php">📚 APPLY FOR COLLEGE PROGRAM </a><i class='bx bxs-pencil'></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <h2 class="section-title">Excellence in Education</h2>
    <p class="section-subtitle">At Philtech, we're committed to providing exceptional educational experiences that prepare students for success.</p>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-icon">📊</div><h3>ABM</h3><p>Accountancy, Business & Management with real-world simulations.</p></div>
        <div class="feature-card"><div class="feature-icon">💻</div><h3>ICT</h3><p>Information Computer Technology · Programming & networking.</p></div>
        <div class="feature-card"><div class="feature-icon">🧠</div><h3>HUMSS</h3><p>Humanities & Social Sciences · critical thinking for leaders.</p></div>
        <div class="feature-card"><div class="feature-icon">📁</div><h3>BSOA</h3><p>Office Administration - professional skills.</p></div>
        <div class="feature-card"><div class="feature-icon">🎓</div><h3>BSCS & BTVTED</h3><p>Computer Science & Technical Teacher Education.</p></div>
    </div>
</div>

<!-- CTA Section - Button removed to have only the two program-specific apply buttons -->
<div class="cta-section">
    <h2>Ready to Start Your Journey?</h2>
    <p>Join thousands of students who have transformed their lives through education at Philtech.</p>
    <!-- No generic apply button here to respect the 2-button requirement for SHS & College -->
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

<!-- Auth Modal -->
<div class="auth-modal">
    <button type="button" class="close-btn-modal"><i class='bx bx-x'></i></button>
    <div class="form-box login">
        <h2>Login</h2>
        <form action="" method="POST">
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
        <form action="" method="POST">
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

<?php if ($flash): ?>
<div class="alert-box show">
    <div class="alert <?= $flash['type'] ?>">
        <i class='bx <?= $flash['type'] === 'success' ? 'bxs-check-circle' : 'bxs-x-circle' ?>'></i>
        <span><?= htmlspecialchars($flash['msg']) ?></span>
    </div>
</div>
<?php endif; ?>

<script src="sample.js"></script>
</body>
</html>