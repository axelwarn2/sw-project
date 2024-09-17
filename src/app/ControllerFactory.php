<?php

namespace App;

use App\Services\FileService;
use App\Utils\FileManager;

class ControllerFactory
{
    public static function create(string $controllerName): object
    {
        $fileManager = new FileManager();
        $fileService = new FileService($fileManager);
        switch ($controllerName) {
            case 'App\Controllers\DirectoryController':
                return new Controllers\DirectoryController(new \App\Models\DirectoryModel(), new \App\Models\FileModel(), $fileService);
            case 'App\Controllers\FileController':
                return new Controllers\FileController(new \App\Models\FileModel(), $fileService);
            case 'App\Controllers\ViewController':
                return new Controllers\ViewController(new \App\Models\DirectoryModel(), new \App\Models\FileModel());
            default:
                throw new \Exception("Контроллер не найден", 500);
        }
    }
}
