<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= SITE_NAME ?> - Login</title>
    <link rel="stylesheet" href="/test-task-sib/user_admin/public/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1><?= SITE_NAME ?></h1>
            <h2>Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/login" class="login-form">
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <div class="login-info">
                <p>Test credentials:</p>
                <p>Login: <strong>admin</strong> / Password: <strong>admin123</strong></p>
            </div>
        </div>
    </div>
</body>
</html>