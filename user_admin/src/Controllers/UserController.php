<?php

namespace App\Controllers;

use App\Services\UserManager;
use App\Auth;
use App\Validators\AgeValidator;

class UserController
{
    private UserManager $userManager;

    public function __construct()
    {
        $pdo = require __DIR__ . '/../../config/database.php';
        $this->userManager = new UserManager($pdo);
    }

    public function index(): void
    {
        // 1. Загружаем конфиг
        $config = require __DIR__ . '/../../config/app.php';

        // 2. Берём из него количество записей на страницу
        $limit = $config['items_per_page'];

        // 3. Остальные параметры
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
        $next_order = $order === 'ASC' ? 'desc' : 'asc';

        $offset = ($page - 1) * $limit;
        $total_users = $this->userManager->getTotalUsers();
        $total_pages = ceil($total_users / $limit);

        // 4. Передаём $limit в UserManager
        $users = $this->userManager->getUsers($offset, $limit, $sort_by, $order);

        // Передаём данные в шаблон
        require __DIR__ . '/../../templates/users/list.php';
    }

    public function show(int $id): void
    {
        $user = $this->userManager->getUserById($id);
        require __DIR__ . '/../../templates/users/detail.php';
    }

    public function store(): void
    {
        $error = AgeValidator::getAgeErrorMessage($_POST['birth_date'] ?? '');
        if ($error) {
            // показать ошибку
        }

        $this->userManager->createUser($_POST);
        header('Location: /users');
    }

    public function update(int $id): void
    {
        $error = AgeValidator::getAgeErrorMessage($_POST['birth_date'] ?? '');
        if ($error) {
            // показать ошибку
        }

        $this->userManager->updateUser($id, $_POST);
        header('Location: /users');
    }
}