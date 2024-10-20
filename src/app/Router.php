<?php

namespace App;

use App\Models\DirectoryModel;
use App\Models\FileModel;
use App\Controllers\Controller;
use App\Responses\Response;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        $config = require __DIR__ . '/config/routes.php';
        $this->routes = $config;
    }

    private function get(string $uri, string $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    private function post(string $uri, string $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function run(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            if (isset($this->routes[$method][$uri])) {
                $action = $this->routes[$method][$uri];
                [$controller, $method] = explode('@', $action);

                $controller = ControllerFactory::create($controller);

                $data = call_user_func([$controller, $method]);

                if (isset($data['view'])) {
                    Response::render($data['view'], $data);
                } else {
                    Response::render('index', $data);
                }
            } else {
                throw new \Exception('Page not found', 404);
            }
        } catch (\Exception $e) {
            Response::render('error', ['error' => $e->getMessage(), 'statusCode' => $e->getCode()]);
        }
    }
}
