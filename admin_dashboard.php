<?php
// admin_dashboard.php
session_start();
require_once 'config.php';

// Simple admin authentication
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
if (!$is_admin && isset($_POST['admin_login'])) {
    $admin_pass = $_POST['admin_password'] ?? '';
    if ($admin_pass === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $is_admin = true;
    } else {
        $error = "Invalid admin password";
    }
}

// Handle status updates
if ($is_admin && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];
    $status = $_POST['status'];
    $table = ($type === 'shs') ? 'shs_clients' : 'college_clients';
    
    $stmt = $conn->prepare("UPDATE $table SET verification_status = ?, admin_notes = ? WHERE id = ?");
    $notes = $_POST['admin_notes'] ?? '';
    $stmt->bind_param("ssi", $status, $notes, $id);
    if ($stmt->execute()) {
        $message = "Status updated successfully!";
    } else {
        $error = "Update failed: " . $conn->error;
    }
}

// Fetch all applications with error handling
$shs_applications = [];
$college_applications = [];

$shs_result = $conn->query("SELECT *, 'shs' as type FROM shs_clients ORDER BY id DESC");
if ($shs_result) {
    $shs_applications = $shs_result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "SHS table not found. Please run the SQL script to create tables.";
}

$college_result = $conn->query("SELECT *, 'college' as type FROM college_clients ORDER BY id DESC");
if ($college_result) {
    $college_applications = $college_result->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "College table not found. Please run the SQL script to create tables.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Application Management</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #f0f2f5;
        }
        .admin-header {
            background: linear-gradient(135deg, #1a2c3e, #2c4b66);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .admin-header h1 {
            font-size: 24px;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }
        .section {
            margin-bottom: 50px;
        }
        .section-title {
            font-size: 28px;
            margin-bottom: 20px;
            color: #1a2c3e;
            border-left: 5px solid #ffb347;
            padding-left: 15px;
        }
        .applications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
        }
        .app-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .app-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .card-header {
            background: linear-gradient(135deg, #ffb347, #ff8c2e);
            padding: 20px;
            color: #1a2c3e;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h3 {
            font-size: 20px;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #ffc107; color: #856404; }
        .status-approved { background: #28a745; color: white; }
        .status-denied { background: #dc3545; color: white; }
        .card-body {
            padding: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        .info-label {
            width: 100px;
            font-weight: 600;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .admin-notes {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 12px;
            margin: 15px 0;
            font-size: 14px;
        }
        .admin-notes textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            font-size: 13px;
        }
        .status-form {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .status-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            flex: 1;
        }
        .update-btn {
            background: #ffb347;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        .update-btn:hover {
            background: #ffa01e;
        }
        .login-form {
            max-width: 400px;
            margin: 100px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-form h2 {
            margin-bottom: 20px;
            color: #1a2c3e;
        }
        .login-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .login-form button {
            background: #ffb347;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 768px) {
            .container { padding: 20px; }
            .applications-grid { grid-template-columns: 1fr; }
            .admin-header { padding: 15px 20px; }
        }
    </style>
</head>
<body>
<?php if (!$is_admin): ?>
    <div class="login-form">
        <h2>🔐 Admin Login</h2>
        <?php if (isset($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
        <form method="POST">
            <input type="password" name="admin_password" placeholder="Enter Admin Password" required>
            <button type="submit" name="admin_login">Login to Dashboard</button>
        </form>
        <p style="margin-top: 15px; font-size: 12px; color: #888;">Default password: admin123</p>
    </div>
<?php else: ?>
    <div class="admin-header">
        <h1><i class='bx bxs-dashboard'></i> Application Management Dashboard</h1>
        <form method="POST" style="margin: 0;">
            <button type="submit" name="admin_logout" class="logout-btn"><i class='bx bx-log-out'></i> Logout</button>
        </form>
    </div>
    
    <div class="container">
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
        
        <!-- SHS Applications Section -->
        <div class="section">
            <h2 class="section-title"><i class='bx bxs-graduation'></i> Senior High School Applications (<?= count($shs_applications) ?>)</h2>
            <div class="applications-grid">
                <?php if (count($shs_applications) > 0): ?>
                    <?php foreach ($shs_applications as $app): ?>
                        <?php $status = $app['verification_status'] ?? 'pending'; ?>
                        <div class="app-card">
                            <div class="card-header">
                                <h3><?= htmlspecialchars($app['name']) ?></h3>
                                <span class="status-badge status-<?= $status === 'approved' ? 'approved' : ($status === 'denied' ? 'denied' : 'pending') ?>">
                                    <?= $status === 'pending' ? 'On Process' : ucfirst($status) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <span class="info-label">Strand:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['strand']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Grade:</span>
                                    <span class="info-value">Grade <?= htmlspecialchars($app['grade']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['email']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phone:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['phone']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Applied:</span>
                                    <span class="info-value"><?= date('M d, Y', strtotime($app['created_at'] ?? 'now')) ?></span>
                                </div>
                                
                                <?php if (!empty($app['admin_notes'])): ?>
                                <div class="admin-notes">
                                    <strong>📝 Admin Notes:</strong><br>
                                    <?= nl2br(htmlspecialchars($app['admin_notes'])) ?>
                                </div>
                                <?php endif; ?>
                                
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                    <input type="hidden" name="type" value="shs">
                                    <select name="status" class="status-select">
                                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>⏳ On Process</option>
                                        <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>✅ Approved</option>
                                        <option value="denied" <?= $status === 'denied' ? 'selected' : '' ?>>❌ Denied</option>
                                    </select>
                                    <input type="text" name="admin_notes" placeholder="Add notes..." value="<?= htmlspecialchars($app['admin_notes'] ?? '') ?>" style="flex: 1; padding: 8px; border-radius: 8px; border: 1px solid #ddd;">
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #888; grid-column: 1/-1; text-align: center;">No SHS applications yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- College Applications Section -->
        <div class="section">
            <h2 class="section-title"><i class='bx bxs-building'></i> College Applications (<?= count($college_applications) ?>)</h2>
            <div class="applications-grid">
                <?php if (count($college_applications) > 0): ?>
                    <?php foreach ($college_applications as $app): ?>
                        <?php $status = $app['verification_status'] ?? 'pending'; ?>
                        <div class="app-card">
                            <div class="card-header">
                                <h3><?= htmlspecialchars($app['name']) ?></h3>
                                <span class="status-badge status-<?= $status === 'approved' ? 'approved' : ($status === 'denied' ? 'denied' : 'pending') ?>">
                                    <?= $status === 'pending' ? 'On Process' : ucfirst($status) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <span class="info-label">Course:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['course']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Year:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['year']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['email']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phone:</span>
                                    <span class="info-value"><?= htmlspecialchars($app['phone']) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Applied:</span>
                                    <span class="info-value"><?= date('M d, Y', strtotime($app['created_at'] ?? 'now')) ?></span>
                                </div>
                                
                                <?php if (!empty($app['admin_notes'])): ?>
                                <div class="admin-notes">
                                    <strong>📝 Admin Notes:</strong><br>
                                    <?= nl2br(htmlspecialchars($app['admin_notes'])) ?>
                                </div>
                                <?php endif; ?>
                                
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                    <input type="hidden" name="type" value="college">
                                    <select name="status" class="status-select">
                                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>⏳ On Process</option>
                                        <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>✅ Approved</option>
                                        <option value="denied" <?= $status === 'denied' ? 'selected' : '' ?>>❌ Denied</option>
                                    </select>
                                    <input type="text" name="admin_notes" placeholder="Add notes..." value="<?= htmlspecialchars($app['admin_notes'] ?? '') ?>" style="flex: 1; padding: 8px; border-radius: 8px; border: 1px solid #ddd;">
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #888; grid-column: 1/-1; text-align: center;">No college applications yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (isset($_POST['admin_logout'])): ?>
        <?php session_destroy(); header("Location: admin_dashboard.php"); exit(); ?>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>