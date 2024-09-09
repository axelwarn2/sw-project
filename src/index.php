<?php

include __DIR__ . '/../vendor/autoload.php';

use App\Router;

$router = new Router();

try {
    $router->run();
} catch (\Exception $e) {
    http_response_code($e->getCode());
    echo "Error: {$e->getMessage()}";
}
