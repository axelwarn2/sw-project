<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\FileModel;
use App\Services\FileService;

class DirectoryController
{
    private DirectoryModel $directoryModel;
    private FileModel $fileModel;
    private FileService $fileService;

    public function __construct(DirectoryModel $directoryModel, FileModel $fileModel, FileService $fileService)
    {
        $this->directoryModel = $directoryModel;
        $this->fileModel = $fileModel;
        $this->fileService = $fileService;
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

        $parentPath = $this->directoryModel->getDirectoryPath($parentId) ?? '';
        $fullPath = $parentPath . '/' . $name;

        $this->fileService->createDirectory($fullPath);
        $this->directoryModel->createDirectory($name, $parentId);
    }

    public function delete()
    {
        $itemId = $_POST['itemId'] ?? null;
        $itemType = $_POST['itemType'] ?? '';
        
        if ($itemType === 'file') {
            $filePath = $this->fileModel->getFilePath($itemId);
            $this->fileModel->deleteFile($itemId);
            $this->fileService->deleteFile($filePath);
        } elseif ($itemType === 'directory') {
            $dirPath = $this->directoryModel->getDirectoryPath($itemId);
            $this->directoryModel->deleteDirectory($itemId);
            $this->fileService->deleteDirectory($dirPath);
        }
    }
}
