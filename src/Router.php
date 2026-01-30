<?php
// src/Router.php

class Router
{
    private array $routes = [];

    public function get(string $path, string $view): void
    {
        $this->addRoute('GET', $path, $view);
    }

    public function post(string $path, string $view): void
    {
        $this->addRoute('POST', $path, $view);
    }


    public function addRoute(string $method, string $path, $view): void
    {
        // Convert route parameters to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^$pattern$#";

        $this->routes[$method][$pattern] = $view;
    }

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = explode('?', $path)[0];
        require_once __DIR__ . "/../db.php";

        foreach ($this->routes[$method] ?? [] as $pattern => $view) {
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($key) => !is_numeric($key),
                    ARRAY_FILTER_USE_KEY
                );

                require __DIR__ . '/../' . $view;
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../views/' . 'error.php';
        return;
    }
}