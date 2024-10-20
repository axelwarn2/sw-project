<?php 

namespace App\Services;

use App\Utils\FileManager;
use App\Interfaces\FileServiceInterface;

class FileService implements FileServiceInterface
{
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function uploadFile(string $tmpName, string $uploadPath): bool
    {
        return $this->fileManager->uploadFile($tmpName, $uploadPath);
    }

    public function deleteFile(string $filePath): bool
    {
        return $this->fileManager->deleteFile($filePath);
    }

    public function createDirectory(string $fullPath): bool
    {
        return $this->fileManager->createDirectory($fullPath);
    }

    public function deleteDirectory(string $directoryPath): bool
    {
        return $this->fileManager->deleteDirectory($directoryPath);
    }

    public function getUploadDir(): string
    {
        return $this->fileManager->getBaseUploadDir();
    }
}
