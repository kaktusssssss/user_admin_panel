<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Delete User</h1>
            <a href="/users" class="btn">← Back to List</a>
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
                    <a href="/users/<?= $user['id'] ?>/delete?confirm=yes" class="btn btn-danger" 
                       style="background: #dc3545;">Yes, Delete This User</a>
                    <a href="/users/<?= $user['id'] ?>" class="btn">Cancel, Go Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>