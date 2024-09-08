<?php

namespace App;

use App\Controllers\DirectoryController;
use App\Controllers\FileController;
use App\Controllers\ViewController;

class ControllerFactory
{
    public static function create($controllerName)
    {
        switch ($controllerName) {
            case 'App\Controllers\DirectoryController':
                return new DirectoryController(new \App\Models\DirectoryModel());
            case 'App\Controllers\FileController':
                return new FileController(new \App\Models\FileModel());
            case 'App\Controllers\ViewController':
                return new ViewController(new \App\Models\DirectoryModel(), new \App\Models\FileModel());
            default:
                throw new \Exception("Контроллер не найден", 500);
        }
    }
}
