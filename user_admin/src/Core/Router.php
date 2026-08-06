<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $controller): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
            echo "Method: $method<br>";
    echo "URI: $uri<br>";
    exit;
    }
}