<?php

namespace App\Utils;

class FileManager
{
    private $baseUploadDir;

    public function __construct()
    {
        $this->baseUploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
    }

    public function createDirectory($path)
    {
        $fullPath = $this->baseUploadDir . '/' . $path;

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        return true;
    }

    public function uploadFile($tmpName, $uploadPath)
    {
        $fullPath = $this->baseUploadDir . $uploadPath;

        $dir = dirname($fullPath);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return move_uploaded_file($tmpName, $fullPath);
    }

    public function deleteFile($filePath)
    {
        $fullPath = $filePath;

        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    public function deleteDirectory($directoryPath)
    {
        $fullPath = $this->baseUploadDir . '/' . $directoryPath;

        if (is_dir($fullPath)) {
            $this->deleteDirectoryRecursively($fullPath);
            return rmdir($fullPath);
        }

        return false;
    }

    public function getBaseUploadDir()
    {
        return $this->baseUploadDir;
    }

    private function deleteDirectoryRecursively($directory)
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
