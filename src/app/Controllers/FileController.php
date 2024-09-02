<?php

namespace App\Controllers;

use App\Models\FileModel;

class FileController
{
    protected FileModel $model;

    public function __construct()
    {
        $this->model = new FileModel();
    }

    public function index()
    {
        require __DIR__ . '/../Views/index.php';
    }

    public function createDirectory()
    {
        $name = $_POST['name'];
        $parentId = $_POST['parentId'] ?? null;
        $this->model->createDirectory($name, $parentId);
        header('Location: /');
    }
}
