<?php
/**
 * Edit user page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $required = ['login', 'first_name', 'last_name', 'gender', 'birth_date'];
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
        $update_data = [
            'login' => $_POST['login'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'gender' => $_POST['gender'],
            'birth_date' => $_POST['birth_date']
        ];
        
        // Add password only if provided
        if (!empty($_POST['password'])) {
            $update_data['password'] = $_POST['password'];
        }
        
        $result = $userManager->updateUser($user_id, $update_data);
        
        if ($result === true) {
            $success = "User updated successfully!";
            // Refresh user data
            $user = $userManager->getUserById($user_id);
            header("refresh:2;url=view.php?id=" . $user_id);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="\test-task-sib\user_admin\css\style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit User</h1>
            <div class="header-actions">
                <span>Welcome, <?= htmlspecialchars($_SESSION['user_login']) ?>!</span>
                <a href="index.php" class="btn">← Back to List</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
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
                <input type="text" name="login" required 
                       value="<?= htmlspecialchars($_POST['login'] ?? $user['login']) ?>">
            </div>
            
            <div class="form-group">
                <label>Password (leave empty to keep current)</label>
                <input type="password" name="password" placeholder="Enter new password only if you want to change it">
                <small>Leave blank to keep existing password</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" required 
                           value="<?= htmlspecialchars($_POST['first_name'] ?? $user['first_name']) ?>">
                           </div>
                
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" required 
                           value="<?= htmlspecialchars($_POST['last_name'] ?? $user['last_name']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="male" <?= (($_POST['gender'] ?? $user['gender']) === 'male') ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= (($_POST['gender'] ?? $user['gender']) === 'female') ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= (($_POST['gender'] ?? $user['gender']) === 'other') ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Birth Date *</label>
                    <input type="date" name="birth_date" required 
                           value="<?= htmlspecialchars($_POST['birth_date'] ?? $user['birth_date']) ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="view.php?id=<?= $user_id ?>" class="btn">Cancel</a>
                <a href="delete.php?id=<?= $user_id ?>" class="btn btn-danger" 
                   onclick="return confirm('Are you sure?')">Delete</a>
            </div>
        </form>
    </div>
</body>
</html>