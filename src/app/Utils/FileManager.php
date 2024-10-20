<?php

namespace App\Utils;

use App\Interfaces\FileManagerInterface;

class FileManager implements FileManagerInterface
{
    private string $baseUploadDir;

    public function __construct()
    {
        $this->baseUploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
    }

    public function createDirectory(string $path): bool
    {
        $fullPath = $this->baseUploadDir . '/' . $path;

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        return true;
    }

    public function uploadFile(string $tmpName, string $uploadPath): bool
    {
        $fullPath = $this->baseUploadDir . $uploadPath;

        $dir = dirname($fullPath);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return move_uploaded_file($tmpName, $fullPath);
    }

    public function deleteFile(string $filePath): bool
    {
        $fullPath = $filePath;

        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    public function deleteDirectory(string $directoryPath): bool
    {
        $fullPath = $this->baseUploadDir . '/' . $directoryPath;

        if (is_dir($fullPath)) {
            $this->deleteDirectoryRecursively($fullPath);
            return rmdir($fullPath);
        }

        return false;
    }

    public function getBaseUploadDir(): string
    {
        return $this->baseUploadDir;
    }

    private function deleteDirectoryRecursively(string $directory): void
    {
        foreach (scandir($directory) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $filePath = $directory . '/' . $item;

            if (is_dir($filePath)) {
                $this->deleteDirectoryRecursively($filePath);
                rmdir($filePath);
            } else {
                unlink($filePath);
            }
        }
    }
}
