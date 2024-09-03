<?php

namespace App;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        $this->get('/', 'App\Controllers\Controller@index');
    }

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            [$controller, $method] = explode('@', $action);
            $controller = new $controller;
            return call_user_func([$controller, $method]);
        } else {
            ?>
            <h2 style="font-size: 30px; text-align: center">404 Not Found</h2>
            <p style="font-size: 20px; text-align: center">Не удалось найти страницу</p>
            <?php
        }
    }
}
