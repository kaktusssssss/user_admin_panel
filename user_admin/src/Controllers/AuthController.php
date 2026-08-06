<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Services\UserManager;

class AuthController
{
    private UserManager $userManager;

    public function __construct()
    {
        $pdo = require __DIR__ . '/../../config/database.php';
        $this->userManager = new UserManager($pdo);
    }

    // Показать форму логина
    public function loginForm(): void
    {
        if (Auth::isLoggedIn()) {
            $config = require __DIR__ . '/../../config/app.php';
            header('Location: ' . $config['base_url'] . '/users');
            exit();
        }

        // Показываем шаблон логина
        require __DIR__ . '/../../templates/auth/login.php';
    }

    // Обработать логин
    public function login(): void
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter both login and password';
            $config = require __DIR__ . '/../../config/app.php';
            header('Location: ' . $config['base_url'] . '/login');
            exit();
        }

        if ($this->userManager->authenticate($login, $password)) {
            unset($_SESSION['login_error']);
            $config = require __DIR__ . '/../../config/app.php';
            header('Location: ' . $config['base_url'] . '/');
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid login or password';
            $config = require __DIR__ . '/../../config/app.php';
            header('Location: ' . $config['base_url'] . '/login');
            exit();
        }
    }

    // Выйти из системы
    public function logout(): void
    {
        Auth::logout();
        $config = require __DIR__ . '/../../config/app.php';
        header('Location: ' . $config['base_url'] . '/login');
        exit;
    }
}