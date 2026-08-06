<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'User Admin Panel' ?></title>
    <link rel="stylesheet" href="/test-task-sib/user_admin/public/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $heading ?? 'User Admin' ?></h1>
            <div class="header-actions">
                <?php if (isset($_SESSION['user_login'])): ?>
                    <span>Welcome, <?= htmlspecialchars($_SESSION['user_login']) ?>!</span>
                    <a href="/users" class="btn">Users List</a>
                    <a href="/users/create" class="btn btn-primary">+ Add New User</a>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                <?php endif; ?>
            </div>
        </div>
        <main>