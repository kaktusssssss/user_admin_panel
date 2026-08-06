<?php
$title = 'Список пользователей';
$heading = 'Registered Users';
include __DIR__ . '/../header.php';
?>
        
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
                    <td><?= $user->getId() ?></td>
                    <td><?= htmlspecialchars($user->getLogin()) ?></td>
                    <td><?= htmlspecialchars($user->getFirstName()) ?></td>
                    <td><?= htmlspecialchars($user->getLastName()) ?></td>
                    <td><?= ucfirst($user->getGender()) ?></td>
                    <td><?= $user->getBirthDate() ?></td>
                    <td>
                        <a href="/users/<?= $user->getId() ?>">View</a>
                        <a href="/users/<?= $user->getId() ?>/edit">Edit</a>
                        <a href="/users/<?= $user->getId() ?>/delete">Delete</a>
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

<?php include __DIR__ . '/../footer.php'; ?>