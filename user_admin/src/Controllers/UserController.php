<?php

namespace App\Controllers;

use App\Services\UserManager;
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
        $config = require __DIR__ . '/../../config/app.php';
        $limit = $config['items_per_page'];

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $sort_by = $_GET['sort'] ?? 'id';
        $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
        $next_order = $order === 'ASC' ? 'desc' : 'asc';

        $offset = ($page - 1) * $limit;
        $total_users = $this->userManager->getTotalUsers();
        $total_pages = ceil($total_users / $limit);

        $users = $this->userManager->getUsers($offset, $limit, $sort_by, $order);

        require __DIR__ . '/../../templates/users/list.php';
    }

    public function show(int $id): void
    {
        $user = $this->userManager->getUserById($id);
        if (!$user) {
            $_SESSION['error'] = 'Пользователь не найден';
            header('Location: /users');
            exit();
        }
        require __DIR__ . '/../../templates/users/detail.php';
    }

    public function store(): void
    {
        $required = ['login', 'password', 'first_name', 'last_name', 'gender', 'birth_date'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Поле {$field} обязательно";
                header('Location: /users/create');
                exit();
            }
        }

        $error = AgeValidator::getAgeErrorMessage($_POST['birth_date'] ?? '');
        if ($error) {
            $_SESSION['error'] = $error;
            header('Location: /users/create');
            exit();
        }

        $result = $this->userManager->createUser($_POST);
        if ($result === true) {
            $_SESSION['success'] = 'Пользователь создан';
            header('Location: /users');
        } else {
            $_SESSION['error'] = $result;
            header('Location: /users/create');
        }
        exit();
    }

    public function update(int $id): void
    {
        $required = ['login', 'first_name', 'last_name', 'gender', 'birth_date'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Поле {$field} обязательно";
                header("Location: /users/{$id}/edit");
                exit();
            }
        }

        $error = AgeValidator::getAgeErrorMessage($_POST['birth_date'] ?? '');
        if ($error) {
            $_SESSION['error'] = $error;
            header("Location: /users/{$id}/edit");
            exit();
        }

        $result = $this->userManager->updateUser($id, $_POST);
        if ($result === true) {
            $_SESSION['success'] = 'Пользователь обновлён';
            header('Location: /users');
        } else {
            $_SESSION['error'] = $result;
            header("Location: /users/{$id}/edit");
        }
        exit();
    }
}