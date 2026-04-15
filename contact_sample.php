
<?php
session_start();

// Check if user is logged in
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$name = $logged_in ? ($_SESSION['name'] ?? null) : null;
$alerts = $_SESSION['alerts'] ?? [];

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";
    
    if (empty($errors)) {
       
        
        $_SESSION['alerts'][] = ['type' => 'success', 'message' => 'Thank you! Your message has been sent successfully.'];
        
        // Clear form data
        $_POST = [];
    } else {
        foreach ($errors as $error) {
            $_SESSION['alerts'][] = ['type' => 'error', 'message' => $error];
        }
    }
    
    header('Location: contact.php');
    exit;
}


$alerts = $_SESSION['alerts'] ?? [];
unset($_SESSION['alerts']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Philtech | Excellence in Education Since 2012</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="contact_sample.css">
    
</head>
<body>
    <header>
        <a href="sample.php" class="logo">PHILTECH</a>
        <nav>
            <a href="sample.php">Home</a>
            <a href="about_sample.php">About</a>
            <a href="service_sample.php">Services</a>
            <a href="contact_sample.php" class="active">Contact</a>
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

    <!-- Hero Section -->
    <section class="contact-hero">
        <h1>Contact Us</h1>
        <p>We're here to answer your questions and help you get started</p>
    </section>

    <!-- Main Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-grid">
                <!-- Left Side - Contact Information -->
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <p>Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    
                    <div class="info-grid">
                        <!-- Main Campus -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class='bx bx-building'></i>
                            </div>
                            <h3>Main Campus</h3>
                            <p>CRDM Building, Congressional Road</p>
                            <p>Maderan, General Mariano Alvarez</p>
                            <p>4117 Cavite</p>
                            <p>Philippines</p>
                        </div>

                        <!-- Phone -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class='bx bx-phone'></i>
                            </div>
                            <h3>Phone</h3>
                            <p><strong>Main:</strong>09972240222</p>
                        </div>

                        <!-- Email -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class='bx bx-envelope'></i>
                            </div>
                            <h3>Email</h3>
                            <p><strong>General:</strong> <a href="https://www.facebook.com/philtechgma2013/">@philtechgma2013</a></p>
                            
                        </div>

                        <!-- Office Hours -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class='bx bx-time'></i>
                            </div>
                            <h3>Office Hours</h3>
                            <p><strong>Monday - Friday:</strong> 8:00 AM - 5:00 PM</p>
                            <p><strong>Saturday:</strong> 8:00 AM - 2:00 PM</p>
                            <p><strong>Sunday:</strong> Closed</p>
                        </div>
                    </div>

                    <!-- Contact by Department -->
                    <div class="departments">
                        <h3>Contact by Department</h3>
                        <div class="department-list">
                            <div class="department-item">
                                <div class="dept-info">
                                    <h4>Admissions Office</h4>
                                    <p>For enrollment and application questions</p>
                                </div>
                                <div class="dept-contact">
                                    <a href="tel:09972240222">09972240222</a>
                                    <small>@philtechgma2013</small>
                                </div>
                            </div>
                            <div class="department-item">
                                <div class="dept-info">
                                    <h4>Student Services</h4>
                                    <p>For current student support</p>
                                </div>
                                <div class="dept-contact">
                                    <a href="tel:+09972240222">+09972240222</a>
                                    <small>students@philtech.edu</small>
                                </div>
                            </div>
                            <div class="department-item">
                                <div class="dept-info">
                                    <h4>General Inquiries</h4>
                                    <p>For general questions and information</p>
                                </div>
                                <div class="dept-contact">
                                    <a href="tel:+09972240222">+09972240222</a>
                                    <small>@philtechgma2013</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              
                <div class="contact-form-container">
                    <h2>Send a Message</h2>
                    <form action="contact.php" method="POST" id="contactForm">
                        <div class="form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input type="text" name="name" placeholder="Enter your name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="your.email@example.com" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Subject <span class="required">*</span></label>
                            <select name="subject" required>
                                <option value="">Select a PROGRAM</option>
                                <option value="College Program" <?= (isset($_POST['subject']) && $_POST['subject'] == 'College Program') ? 'selected' : '' ?>>College Program</option>
                                <option value="Shs Program" <?= (isset($_POST['subject']) && $_POST['subject'] == 'Shs Program') ? 'selected' : '' ?>>Shs Program</option>
                                
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Message <span class="required">*</span></label>
                            <textarea name="message" placeholder="Type your message..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        
                        <button type="submit" name="send_message" class="submit-btn">
                            Send Message <i class='bx bx-send'></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="map-container">
            <div class="map-placeholder">
                <i class='bx bx-map-alt'></i>
                <a class= "map-link"href="https://www.google.com/maps/place/Philtech/@14.2837343,120.9965205,17z/data=!3m1!4b1!4m6!3m5!1s0x3397d604cb43d9bf:0x52511a523c76345!8m2!3d14.2837291!4d120.9990954!16s%2Fg%2F11bx1rzt1t?entry=ttu&g_ep=EgoyMDI2MDMyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="map-btn">
                    Open in Google Maps <i class='bx bx-link-external'></i>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="contact-cta">
        <h2>Ready to Get Started?</h2>
        <p>Have questions about programs, admissions, or campus life? We're here to help!</p>
        <div class="cta-buttons">
            <a href="#" class="cta-btn-primary">Send an Inquiry</a>
            <a href="#" class="cta-btn-secondary">Start Application</a>
        </div>
    </section>

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
                <a href="service_sample.php">Services</a>
                <a href="contact.php">Contact</a>
            </div>
            <div class="footer-col">
                <h4>Contact Info</h4>
                <p><i class='bx bx-map'></i> 📍 CRDM Building, Congressional Rd, Maderan, General Mariano Alvarez, 4117 Cavite</p>
                <p><i class='bx bx-phone'></i> 📞 09972240222</p>
                <p><i class='bx bx-envelope'></i> ✉️<a href="https://www.facebook.com/philtechgma2013/"></a> @philtechgma2013</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Philtech Technological Institution. All rights reserved.</p>
        </div>
    </footer>

    <!-- Alert Messages -->
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

    <!-- Auth Modal -->
    <div class="auth-modal">
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
