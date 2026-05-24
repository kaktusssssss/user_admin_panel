<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/UserManager.php';

requireAuth();

$userManager = new UserManager(getDBConnection());

// Get pagination and sorting parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$next_order = $order === 'ASC' ? 'desc' : 'asc';

$offset = ($page - 1) * ITEMS_PER_PAGE;
$total_users = $userManager->getTotalUsers();
$total_pages = ceil($total_users / ITEMS_PER_PAGE);

$users = $userManager->getUsers($offset, ITEMS_PER_PAGE, $sort_by, $order);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - Users List</title>
    <link rel="stylesheet" href="\test-task-sib\user_admin\css\style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registered Users</h1>
            <div class="header-actions">
                <span>Welcome, <?= htmlspecialchars($_SESSION['user_login']) ?>!</span>
                <a href="add.php" class="btn btn-primary">+ Add New User</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th><a href="?sort=id&order=<?= $next_order ?>&page=<?= $page ?>">ID <?= $sort_by === 'id' ? ($order === 'ASC' ? '↑' : '↓') : '' ?></a></th>
                        <th><a href="?sort=login&order=<?= $next_order ?>&page=<?= $page ?>">Login <?= $sort_by === 'login' ? ($order === 'ASC' ? '↑' : '↓') : '' ?></a></th>
                        <th><a href="?sort=first_name&order=<?= $next_order ?>&page=<?= $page ?>">First Name <?= $sort_by === 'first_name' ? ($order === 'ASC' ? '↑' : '↓') : '' ?></a></th>
                        <th><a href="?sort=last_name&order=<?= $next_order ?>&page=<?= $page ?>">Last Name <?= $sort_by === 'last_name' ? ($order === 'ASC' ? '↑' : '↓') : '' ?></a></th>
                        <th>Gender</th>
                        <th><a href="?sort=birth_date&order=<?= $next_order ?>&page=<?= $page ?>">Birth Date <?= $sort_by === 'birth_date' ? ($order === 'ASC' ? '↑' : '↓') : '' ?></a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['login']) ?></td>
                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                        <td><?= ucfirst($user['gender']) ?></td>
                        <td><?= date('Y-m-d', strtotime($user['birth_date'])) ?></td>
                        <td class="actions">
                            <a href="view.php?id=<?= $user['id'] ?>" class="btn-small btn-view">View</a>
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn-small btn-edit">Edit</a>
                            <a href="delete.php?id=<?= $user['id'] ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page-1 ?>&sort=<?= $sort_by ?>&order=<?= strtolower($order) ?>">« Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&sort=<?= $sort_by ?>&order=<?= strtolower($order) ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page+1 ?>&sort=<?= $sort_by ?>&order=<?= strtolower($order) ?>">Next »</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>