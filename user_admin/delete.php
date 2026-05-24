<?php
/**
 * Delete user script
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

// Get user info for confirmation message
$user = $userManager->getUserById($user_id);

if (!$user) {
    $_SESSION['error'] = 'User not found';
    header('Location: index.php');
    exit();
}

// Handle confirmation
$confirmed = isset($_GET['confirm']) && $_GET['confirm'] === 'yes';

if ($confirmed) {
    $result = $userManager->deleteUser($user_id);
    
    if ($result === true) {
        $_SESSION['success'] = 'User "' . htmlspecialchars($user['login']) . '" has been deleted successfully';
    } else {
        $_SESSION['error'] = $result;
    }
    
    header('Location: index.php');
    exit();
}

// If not confirmed, show confirmation page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="\test-task-sib\user_admin\css\style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Delete User</h1>
            <a href="index.php" class="btn">← Back to List</a>
        </div>
        
        <div class="alert alert-error" style="background: #fff3cd; border-color: #ffc107; color: #856404;">
            <strong>Warning!</strong> You are about to delete a user. This action cannot be undone.
        </div>
        
        <div class="user-details">
            <div class="detail-card">
                <h3>User to delete:</h3>
                <div class="detail-row">
                    <span class="detail-label">Login:</span>
                    <span class="detail-value"><strong><?= htmlspecialchars($user['login']) ?></strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Registered:</span>
                    <span class="detail-value"><?= date('Y-m-d', strtotime($user['created_at'])) ?></span>
                </div>
                
                <div class="form-actions" style="margin-top: 30px;">
                    <a href="delete.php?id=<?= $user_id ?>&confirm=yes" class="btn btn-danger" 
                       style="background: #dc3545;">Yes, Delete This User</a>
                    <a href="view.php?id=<?= $user_id ?>" class="btn">Cancel, Go Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>