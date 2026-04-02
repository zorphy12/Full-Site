<?php
// profile_card.php - View application status with profile card
session_start();
require_once 'config.php';

$application = null;
$error = null;
$type = $_GET['type'] ?? '';
$email = $_GET['email'] ?? '';

if ($type && $email) {
    $table = ($type === 'shs') ? 'shs_clients' : 'college_clients';
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    
    if (!$application) {
        $error = "No application found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status - Philtech</title>
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
            max-width: 600px;
            margin: 0 auto;
        }
        .search-card, .profile-card {
            background: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }
        .search-card h2 {
            color: #1a2c3e;
            margin-bottom: 20px;
            text-align: center;
        }
        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .search-form select, .search-form input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 16px;
            transition: 0.3s;
        }
        .search-form select:focus, .search-form input:focus {
            outline: none;
            border-color: #ffb347;
        }
        .search-btn {
            background: #ffb347;
            border: none;
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-btn:hover {
            background: #ffa01e;
            transform: translateY(-2px);
        }
        .profile-card {
            text-align: center;
        }
        .avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ffb347, #ff8c2e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .avatar i {
            font-size: 50px;
            color: white;
        }
        .profile-card h2 {
            color: #1a2c3e;
            margin-bottom: 5px;
        }
        .profile-card .email {
            color: #666;
            margin-bottom: 20px;
        }
        .status-container {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 600;
            margin: 20px 0;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-denied { background: #f8d7da; color: #721c24; }
        .info-section {
            text-align: left;
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-label {
            width: 120px;
            font-weight: 600;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .admin-note {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 15px;
            margin-top: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: white;
            text-decoration: none;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
        }
        @media (max-width: 480px) {
            .search-card, .profile-card { padding: 25px; }
            .search-form { flex-direction: column; }
            .info-label { width: 100px; font-size: 14px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="search-card">
        <h2><i class='bx bxs-id-card'></i> Check Application Status</h2>
        <form method="GET" class="search-form">
            <select name="type" required>
                <option value="">Select Application Type</option>
                <option value="shs" <?= $type === 'shs' ? 'selected' : '' ?>>Senior High School</option>
                <option value="college" <?= $type === 'college' ? 'selected' : '' ?>>College</option>
            </select>
            <input type="email" name="email" placeholder="Enter your email address" value="<?= htmlspecialchars($email) ?>" required>
            <button type="submit" class="search-btn"><i class='bx bx-search'></i> Check Status</button>
        </form>
    </div>
    
    <?php if ($error): ?>
        <div class="profile-card">
            <div class="error">
                <i class='bx bxs-error-circle' style="font-size: 40px;"></i>
                <p><?= $error ?></p>
            </div>
        </div>
    <?php elseif ($application): 
        $status = $application['verification_status'] ?? 'pending';
        $statusText = $status === 'approved' ? 'Approved' : ($status === 'denied' ? 'Denied' : 'On Process');
    ?>
        <div class="profile-card">
            <div class="avatar">
                <i class='bx bxs-user-circle'></i>
            </div>
            <h2><?= htmlspecialchars($application['name']) ?></h2>
            <div class="email"><?= htmlspecialchars($application['email']) ?></div>
            
            <div class="status-container status-<?= $status ?>">
                <i class='bx <?= $status === 'approved' ? 'bxs-check-circle' : ($status === 'denied' ? 'bxs-x-circle' : 'bxs-hourglass') ?>'></i>
                Status: <?= $statusText ?>
            </div>
            
            <div class="info-section">
                <h3 style="margin-bottom: 15px;">Application Details</h3>
                
                <?php if ($type === 'shs'): ?>
                    <div class="info-row">
                        <span class="info-label">Strand:</span>
                        <span class="info-value"><?= htmlspecialchars($application['strand']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Grade Level:</span>
                        <span class="info-value">Grade <?= htmlspecialchars($application['grade']) ?></span>
                    </div>
                <?php else: ?>
                    <div class="info-row">
                        <span class="info-label">Course:</span>
                        <span class="info-value"><?= htmlspecialchars($application['course']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Year Level:</span>
                        <span class="info-value"><?= htmlspecialchars($application['year']) ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value"><?= htmlspecialchars($application['phone']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age:</span>
                    <span class="info-value"><?= htmlspecialchars($application['age']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender:</span>
                    <span class="info-value"><?= htmlspecialchars($application['gender']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Application Date:</span>
                    <span class="info-value"><?= date('F d, Y', strtotime($application['created_at'] ?? 'now')) ?></span>
                </div>
                
                <?php if (!empty($application['admin_notes'])): ?>
                <div class="admin-note">
                    <strong><i class='bx bxs-message-dots'></i> Admin Note:</strong><br>
                    <?= nl2br(htmlspecialchars($application['admin_notes'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div style="text-align: center;">
        <a href="sample.php" class="back-link"><i class='bx bx-arrow-back'></i> Back to Home</a>
    </div>
</div>
</body>
</html> 