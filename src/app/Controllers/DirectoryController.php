<?php

namespace App\Controllers;

use App\Models\DirectoryModel;

class DirectoryController
{
    private DirectoryModel $directoryModel;

    public function __construct(DirectoryModel $directoryModel)
    {
        $this->directoryModel = $directoryModel;
    }

    public function createDirectory()
    {
        $parentId = $_POST['parentId'] ?? null;
        $name = $_POST['name'] ?? '';
    
        if (empty($name)) {
           throw new \Exception("Имя каталога не может быть пустым", 400);
        }

        if (strlen($name) > 255) {
            throw new \Exception("Имя каталога не должно превышать 255 символов", 400);
        }

        $this->directoryModel->createDirectory($name, $parentId);
    }

    public function delete()
    {
        $itemId = $_POST['itemId'] ?? null;

        $this->directoryModel->deleteDirectory($itemId);
    }
}
