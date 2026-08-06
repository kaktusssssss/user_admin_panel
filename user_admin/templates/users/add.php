<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add New User</h1>
            <a href="/users" class="btn">← Back to List</a>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/users/store" class="user-form">
            <div class="form-group">
                <label>Login *</label>
                <input type="text" name="login" required value="<?= htmlspecialchars($login ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" required value="<?= htmlspecialchars($firstName ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" required value="<?= htmlspecialchars($lastName ?? '') ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="male" <?= ($gender ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= ($gender ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= ($gender ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Birth Date *</label>
                    <input type="date" name="birth_date" required value="<?= htmlspecialchars($birthDate ?? '') ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="/users" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>