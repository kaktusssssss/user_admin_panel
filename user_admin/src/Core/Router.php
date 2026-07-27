<?php

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
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                [$controllerClass, $action] = $route['controller'];
                $controller = new $controllerClass();
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}