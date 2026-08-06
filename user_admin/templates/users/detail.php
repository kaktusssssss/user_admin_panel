<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Details</h1>
            <div class="header-actions">
                <span>Welcome, <?= htmlspecialchars($_SESSION['user_login']) ?>!</span>
                <a href="/users" class="btn">← Back to List</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="user-details">
            <div class="detail-card">
                <div class="detail-header">
                    <h2><?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?></h2>
                    <div class="detail-actions">
                        <a href="/users/<?= $user->getId() ?>/edit" class="btn btn-primary">Edit User</a>
                        <a href="/users/<?= $user->getId() ?>/delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</a>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Account Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">User ID:</span>
                        <span class="detail-value"><?= $user->getId() ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Login:</span>
                        <span class="detail-value"><?= htmlspecialchars($user->getLogin()) ?></span>
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
                        <span class="detail-value"><?= htmlspecialchars($user->getFirstName()) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Last Name:</span>
                        <span class="detail-value"><?= htmlspecialchars($user->getLastName()) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Gender:</span>
                        <span class="detail-value"><?= ucfirst($user->getGender()) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date of Birth:</span>
                        <span class="detail-value"><?= date('F j, Y', strtotime($user->getBirthDate())) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Age:</span>
                        <span class="detail-value">
                            <?php 
                                $age = date_diff(date_create($user->getBirthDate()), date_create('today'))->y;
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