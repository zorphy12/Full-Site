<?php
    $host = "localhost";
    $username = "root";
    $password = ""; 
    $database = "users_db";  
    
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //connect to database
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check and add status columns if needed
    $result = $conn->query("SHOW COLUMNS FROM shs_clients LIKE 'verification_status'");
    if ($result && $result->num_rows == 0) {
        $conn->query("ALTER TABLE shs_clients ADD COLUMN verification_status ENUM('pending', 'approved', 'denied') DEFAULT 'pending'");
        $conn->query("ALTER TABLE shs_clients ADD COLUMN admin_notes TEXT");
        $conn->query("ALTER TABLE shs_clients ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }

    $name = "";
    $email = "";
    $address = "";
    $phone = "";
    $birthdate = "";
    $age = "";
    $gender = "";
    $strand = "";
    $grade = ""; 

    $errorMessage = "";
    $successMessage = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $birthdate = $_POST['birthdate'] ?? '';
        $age = $_POST['age'] ?? '';
        $gender = trim($_POST['gender'] ?? '');
        $strand = $_POST['strand'] ?? '';
        $grade = $_POST['grade'] ?? '';
        
        do{
            if(empty($name) || empty($email) || empty($address) || empty($phone) || empty($birthdate) || empty($age) || empty($gender) || empty($strand) || empty($grade)){
                $errorMessage = "All fields are required";
                break;
            }
            
            // Validate email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Please enter a valid email address";
                break;
            }
            
            // Validate age
            if($age < 15 || $age > 100) {
                $errorMessage = "Age must be between 15 and 100";
                break;
            }
            
            // Using prepared statement
            $sql = "INSERT INTO shs_clients (name, email, address, phone, birthdate, age, gender, strand, grade, verification_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
            
            $stmt = $conn->prepare($sql);
            if(!$stmt) {
                $errorMessage = "Prepare failed: " . $conn->error;
                break;
            }
            
            $stmt->bind_param("sssssisss", $name, $email, $address, $phone, $birthdate, $age, $gender, $strand, $grade);
            
            if($stmt->execute()) {
                $successMessage = "Application submitted successfully! You can now check your status.";
                
                // Clear form
                $name = $email = $address = $phone = $birthdate = $age = $gender = $strand = $grade = "";
            } else {
                $errorMessage = "Error submitting application: " . $stmt->error;
                break;
            }
            
            $stmt->close();

        } while(false);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHS Application - Philtech</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-card {
            background: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .form-card h1 {
            color: #1a2c3e;
            margin-bottom: 10px;
            text-align: center;
        }
        .form-card .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 16px;
            transition: 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #ffb347;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #ffb347, #ff8c2e);
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            color: #1a2c3e;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 140, 0, 0.3);
        }
        .alert {
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .status-link {
            text-align: center;
            margin-top: 20px;
        }
        .status-link a {
            color: #ffb347;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .form-card { padding: 25px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <h1><i class='bx bxs-graduation'></i> Senior High School Application</h1>
        <div class="subtitle">Apply for SHS Program at Philtech</div>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= $errorMessage ?></div>
        <?php endif; ?>
        
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= $successMessage ?>
                <br><br>
                <a href="profile_card.php?type=shs&email=<?= urlencode($_POST['email'] ?? '') ?>" style="color: #155724; font-weight: 600;">Click here to view your application status →</a>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name" value="<?= htmlspecialchars($name) ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="09123456789" value="<?= htmlspecialchars($phone) ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" placeholder="Your complete address" value="<?= htmlspecialchars($address) ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" value="<?= $birthdate ?>" required>
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" min="15" max="100" placeholder="Age" value="<?= $age ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Strand</label>
                    <select name="strand" required>
                        <option value="">Select Strand</option>
                        <option value="ABM" <?= $strand == 'ABM' ? 'selected' : '' ?>>ABM - Accountancy & Business</option>
                        <option value="ICT" <?= $strand == 'ICT' ? 'selected' : '' ?>>ICT - Information Technology</option>
                        <option value="HUMSS" <?= $strand == 'HUMSS' ? 'selected' : '' ?>>HUMSS - Humanities & Social Sciences</option>
                        <option value="H.E" <?= $strand == 'H.E' ? 'selected' : '' ?>>H.E - Home Economics</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Grade Level</label>
                <select name="grade" required>
                    <option value="">Select Grade</option>
                    <option value="11" <?= $grade == '11' ? 'selected' : '' ?>>Grade 11</option>
                    <option value="12" <?= $grade == '12' ? 'selected' : '' ?>>Grade 12</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn"><i class='bx bxs-send'></i> Submit Application</button>
        </form>
        
        <div class="status-link">
            <a href="profile_card.php">📋 Check your application status →</a>
        </div>
    </div>
</div>
</body>
</html>