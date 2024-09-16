<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\FileModel;
use App\Services\FileService;
use App\Responses\Response;

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

    public function createDirectory(): array
    {
        $parentId = $_POST['parentId'] ?? null;
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            return ['error' => 'Название каталога не может быть пустым', 'statusCode' => 400];
        }

        if (strlen($name) > 255) {
            return ['error' => 'Название каталога слишком длинное', 'statusCode' => 400];
        }

        $parentPath = $this->directoryModel->getDirectoryPath($parentId) ?? '';
        $fullPath = $parentPath . '/' . $name;

        $this->fileService->createDirectory($fullPath);
        $this->directoryModel->createDirectory($name, $parentId);

        return [
            'view' => 'index', 
            'directoryTree' => $this->directoryModel->getDirectoryTree()
        ];
    }

    public function delete(): array
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

        return [
            'view' => 'index', 
            'directoryTree' => $this->directoryModel->getDirectoryTree()
        ];
    }
}
