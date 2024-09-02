<?php

include __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\Controllers\FileController;

$router = new Router();

require __DIR__ . '/routes/web.php';

$router->dispatch();
