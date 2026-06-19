<?php
/**
 * View user details page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/UserManager.php';

requireAuth();

$userManager = new UserManager(getDBConnection());
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) {
    $_SESSION['error'] = 'Invalid user ID';
    header('Location: index.php');
    exit();
}

$user = $userManager->getUserById($user_id);

if (!$user) {
    $_SESSION['error'] = 'User not found';
    header('Location: index.php');
    exit();
}

// Format date of birth
$birth_date = date('F j, Y', strtotime($user['birth_date']));
$created_at = date('F j, Y H:i:s', strtotime($user['created_at']));

// Format gender for display
$gender_display = ucfirst($user['gender']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="\test-task-sib\user_admin\css\style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Details</h1>
            <div class="header-actions">
                <span>Welcome, <?= htmlspecialchars($_SESSION['user_login']) ?>!</span>
                <a href="index.php" class="btn">← Back to List</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="user-details">
            <div class="detail-card">
                <div class="detail-header">
                    <h2><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                    <div class="detail-actions">
                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-primary">Edit User</a>
                        <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</a>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Account Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">User ID:</span>
                        <span class="detail-value"><?= $user['id'] ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Login:</span>
                        <span class="detail-value"><?= htmlspecialchars($user['login']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Registered on:</span>
                        <span class="detail-value"><?= $created_at ?></span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Personal Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">First Name:</span>
                        <span class="detail-value"><?= htmlspecialchars($user['first_name']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Last Name:</span>
                        <span class="detail-value"><?= htmlspecialchars($user['last_name']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Gender:</span>
                        <span class="detail-value"><?= $gender_display ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date of Birth:</span>
                        <span class="detail-value"><?= $birth_date ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Age:</span>
                        <span class="detail-value">
                            <?php 
                                $age = date_diff(date_create($user['birth_date']), date_create('today'))->y;
                                echo $age . ' years';
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>