<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\FileModel;

class DirectoryController
{
    private DirectoryModel $directoryModel;
    private FileModel $fileModel;

    public function __construct(DirectoryModel $directoryModel, FileModel $fileModel)
    {
        $this->directoryModel = $directoryModel;
        $this->fileModel = $fileModel;
    }

    public function createDirectory()
    {
        $parentId = $_POST['parentId'] ?? null;
        $name = $_POST['name'] ?? '';
    
        if (empty($name)) {
           http_response_code(400);
           return;
        }

        if (strlen($name) > 255) {
            http_response_code(400);
            return;
        }

        $this->directoryModel->createDirectory($name, $parentId);
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
