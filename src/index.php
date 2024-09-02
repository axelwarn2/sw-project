<?php

include __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\Controllers\FileController;

$router = new Router();
$router->dispatch();
