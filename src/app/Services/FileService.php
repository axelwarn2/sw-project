<?php 

namespace App\Services;

use App\Utils\FileManager;

class FileService
{
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function uploadFile($tmpName, $uploadPath)
    {
        return $this->fileManager->uploadFile($tmpName, $uploadPath);
    }

    public function deleteFile($filePath)
    {
        return $this->fileManager->deleteFile($filePath);
    }

    public function createDirectory($fullPath)
    {
        return $this->fileManager->createDirectory($fullPath);
    }

    public function deleteDirectory($directoryPath)
    {
        return $this->fileManager->deleteDirectory($directoryPath);
    }

    public function getUploadDir()
    {
        return $this->fileManager->getBaseUploadDir();
    }
}
