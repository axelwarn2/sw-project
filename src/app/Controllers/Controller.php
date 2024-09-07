<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\FileModel;
use App\Views\RenderDirectoryTree;

class Controller
{
    private DirectoryModel $directoryModel;
    private FileModel $fileModel;

    public function __construct(DirectoryModel $directoryModel, FileModel $fileModel)
    {
        $this->directoryModel = $directoryModel;
        $this->fileModel = $fileModel;
    }
    public function index()
    {   
        $directories = $this->directoryModel->getDirectories();
        $files = $this->fileModel->getFiles();

        $render = new RenderDirectoryTree();
        $directoryTree  = $render->render($directories, $files);
        
        return [
            "directoryTree" => $directoryTree ,
        ];
    }

    public function createDirectory()
    {
        $parentId = $_POST['parentId'] ?? null;
        $name = $_POST['name'] ?? '';
    
        if (!empty($name)) {
           throw new \Exception("Имя каталога не может быть пустым", 400);
        }

        if (strlen($name) > 255) {
            throw new \Exception("Имя каталога не должно превышать 255 символов", 400);
        }

        $this->directoryModel->createDirectory($name, $parentId);
    }
    
    public function createFile()
    {
        if (isset($_FILES['file'])) {
            $directoryId = $_POST['directoryId'] ?? null;
            $filename = $_FILES['file']['name'];
            $tmpName = $_FILES['file']['tmp_name'];

            if (!empty($filename) && $directoryId) {
                $uploadDir = dirname(__DIR__, 2) . '/uploads/';
                $uploadFile = $uploadDir . $filename;

                move_uploaded_file($tmpName, $uploadFile);
                $this->fileModel->createFile($filename, $directoryId);
            }
        }
    }    

    public function delete()
    {
        $itemId = $_POST['itemId'] ?? null;
        $itemType = $_POST['itemType'] ?? '';

        if ($itemType === 'file') {
            $this->fileModel->deleteFile($itemId);
        } elseif ($itemType === 'directory') {
            $this->directoryModel->deleteDirectory($itemId);
        }
    }
}
