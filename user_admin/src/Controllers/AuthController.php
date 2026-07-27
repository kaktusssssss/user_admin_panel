<?php

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
            header('Location: /users');
            exit();
        }

        require __DIR__ . '/../../public/login.php';
    }

    // Обработать логин
    public function login(): void
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter both login and password';
            header('Location: /login');
            exit();
        }

        $pdo = require __DIR__ . '/../../config/database.php';
        $userManager = new UserManager($pdo);

        if ($userManager->authenticate($login, $password)) {
            unset($_SESSION['login_error']);
            header('Location: /');
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid login or password';
            header('Location: /login');
            exit();
        }
    }

    // Выйти из системы
    public function logout(): void
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}