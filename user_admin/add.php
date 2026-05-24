<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/UserManager.php';

requireAuth();

$error = '';
$success = '';
$userManager = new UserManager(getDBConnection());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $required = ['login', 'password', 'first_name', 'last_name', 'gender', 'birth_date'];
    $valid = true;
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = "Please fill all required fields";
            $valid = false;
            break;
        }
    }
    
    // Validate date
    if ($valid && !strtotime($_POST['birth_date'])) {
        $error = "Invalid birth date";
        $valid = false;
    }
    
    // Validate age (18-100 y.o.)
    if ($valid) {
        $age_error = getAgeErrorMessage($_POST['birth_date'], 18, 100);
        if ($age_error){
            $error = $age_error;
            $valid = false;
        }
    }

    // Validate gender
    if ($valid && !in_array($_POST['gender'], ['male', 'female', 'other'])) {
        $error = "Invalid gender selection";
        $valid = false;
    }

    if ($valid) {
        $result = $userManager->createUser($_POST);
        if ($result === true) {
            $success = "User created successfully!";
            // Redirect after 2 seconds or provide link
            header("refresh:2;url=index.php");
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="\test-task-sib\user_admin\css\style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add New User</h1>
            <a href="index.php" class="btn">← Back to List</a>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="user-form">
            <div class="form-group">
                <label>Login *</label>
                <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= ($_POST['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Birth Date *</label>
                    <input type="date" name="birth_date" required value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="index.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>