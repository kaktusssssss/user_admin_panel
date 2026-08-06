<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;

$controller = new UserController();
$controller->show((int)$_GET['id'] ?? 0);