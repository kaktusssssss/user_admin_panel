<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserManager;
use App\Validators\AgeValidator;
use App\Auth;
use PDO;

class UserController
{
    private UserManager $userManager;

    public function __construct(PDO $pdo)
    {
        $this->userManager = new UserManager($pdo);
    }

    public function index(): void
    {
        Auth::requireAuth();

        $config = require __DIR__ . '/../../config/app.php';
        $limit = $config['items_per_page'];

        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $limit;
        $sort_by = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        $users = $this->userManager->getUsers($offset, $limit, $sort_by, $order);
        $total_users = $this->userManager->getTotalUsers();
        $total_pages = ceil($total_users / $limit);

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

    public function create(): void
    {
        // Если уже есть ошибки или успех из сессии — передаём в шаблон
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);

        $login = $_POST['login'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birthDate = $_POST['birth_date'] ?? '';

        require __DIR__ . '/../../templates/users/add.php';
    }

    public function edit(int $id): void
    {
        $user = $this->userManager->getUserById($id);

        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: /users');
            exit();
        }

        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);

        // Явно передаём переменные
        include __DIR__ . '/../../templates/users/edit.php';
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

    public function destroy(int $id): void
    {
        $userManager = $this->userManager;
        $user = $userManager->getUserById($id);

        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: /users');
            exit();
        }

        // Если подтверждено — удаляем
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $result = $userManager->deleteUser($id);
            
            if ($result === true) {
                $_SESSION['success'] = 'User "' . htmlspecialchars($user['login']) . '" has been deleted successfully';
            } else {
                $_SESSION['error'] = $result;
            }
            
            header('Location: /users');
            exit();
        }

        // Иначе показываем страницу подтверждения
        $user = $userManager->getUserById($id);
        require __DIR__ . '/../../templates/users/delete.php';
    }
}