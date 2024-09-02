<?php

namespace App;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        $this->get('/', 'App\Controllers\FileController@index');
    }

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            [$controller, $method] = explode('@', $action);
            $controller = new $controller;
            return call_user_func([$controller, $method]);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}
