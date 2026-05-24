<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/UserManager.php';

// If already logged in, redirect to user list
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$userManager = new UserManager(getDBConnection());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        $error = 'Please enter both login and password';
    } else {
        if ($userManager->authenticate($login, $password)) {
            header('Location: index.php');
            exit();
        } else {
            $error = 'Invalid login or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - Login</title>
    <link rel="stylesheet" href="\test-task-sib\user_admin\css\style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1><?= SITE_NAME ?></h1>
            <h2>Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <div class="login-info">
                <p>Test credentials:</p>
                <p>Login: <strong>admin</strong> / Password: <strong>admin123</strong></p>
            </div>
        </div>
    </div>
</body>
</html>