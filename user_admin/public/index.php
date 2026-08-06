<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../routes/web.php';

use App\Controllers\UserController;

$pdo = require __DIR__ . '/../config/database.php';
$controller = new UserController($pdo);
$controller->index();

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);