<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;

$router = new Router();

// Главная
$router->add('GET', '/', [UserController::class, 'index']);

// Пользователи
$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/users/create', [UserController::class, 'create']);
$router->add('POST', '/users', [UserController::class, 'store']);
$router->add('GET', '/users/{id}', [UserController::class, 'show']);
$router->add('GET', '/users/{id}/edit', [UserController::class, 'edit']);
$router->add('POST', '/users/{id}', [UserController::class, 'update']);
$router->add('POST', '/users/{id}/delete', [UserController::class, 'destroy']);

// Аутентификация
$router->add('GET', '/login', [AuthController::class, 'loginForm']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);