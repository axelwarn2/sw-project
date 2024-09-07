<?php

namespace App;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        $this->get('/', 'App\Controllers\Controller@index');
        $this->post('/create-directory', 'App\Controllers\Controller@createDirectory');
        $this->post('/create-file', 'App\Controllers\Controller@createFile');
        $this->post('/delete', 'App\Controllers\Controller@delete');
    }

    private function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    private function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            [$controller, $method] = explode('@', $action);
            $controller = new $controller;
            $data = call_user_func([$controller, $method]);
            include __DIR__ . '/Views/index.php';
        } else {
            throw new \Exception('Страница не найдена', 404);
        }
    }
}
