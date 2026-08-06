<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit User</h1>
            <div class="header-actions">
                <span>Welcome, <?= htmlspecialchars($_SESSION['user_login']) ?>!</span>
                <a href="/users" class="btn">← Back to List</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/users/<?= $user['id'] ?>/update" class="user-form">
            <div class="form-group">
                <label>Login *</label>
                <input type="text" name="login" required 
                       value="<?= htmlspecialchars($user['login']) ?>">
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
                           value="<?= htmlspecialchars($user['first_name']) ?>">
                </div>
                
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" required 
                           value="<?= htmlspecialchars($user['last_name']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Birth Date *</label>
                    <input type="date" name="birth_date" required 
                           value="<?= htmlspecialchars($user['birth_date']) ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="/users/<?= $user['id'] ?>" class="btn">Cancel</a>
                <a href="/users/<?= $user['id'] ?>/delete" class="btn btn-danger" 
                   onclick="return confirm('Are you sure?')">Delete</a>
            </div>
        </form>
    </div>
</body>
</html>