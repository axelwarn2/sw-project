<?php

namespace App;

use App\Models\DirectoryModel;
use App\Models\FileModel;
use App\Controllers\Controller;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        $this->loadRoutes();
    }

    private function loadRoutes() 
    {
        $config = require __DIR__ . '/config/routes.php';
        $this->routes = $config;
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

            $directoryModel = new DirectoryModel();
            $fileModel = new FileModel();
            $controller = new Controller($directoryModel, $fileModel);

            $data = call_user_func([$controller, $method]);
            require __DIR__ . '/Views/index.php';
        } else {
            throw new \Exception('Page not found', 404);
        }
    }
}
