<?php

namespace App\Controllers;

use App\Models\FileModel;
use App\Services\FileService;

class FileController
{
    private FileModel $fileModel;
    private FileService $fileService;
    private $maxFileSize = 20 * 1024 * 1024;
    private $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf'];

    public function __construct(FileModel $fileModel, FileService $fileService)
    {
        $this->fileModel = $fileModel;
        $this->fileService = $fileService;
    }

    public function createFile()
    {
        if (isset($_FILES['file'])) {
            $directoryId = $_POST['directoryId'] ?? null;
            $filename = $_FILES['file']['name'];
            $tmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

            if ($fileSize > $this->maxFileSize) {
                http_response_code(413);
                return;
            }

            if (!in_array($fileExtension, $this->allowedFileExtensions)) {
                http_response_code(400);
                return;
            }

            if ($directoryId) {
                $directoryPath = $this->fileModel->getParentPath($directoryId);
                $uploadPath = $directoryPath . '/' . $filename;

                $this->fileService->createDirectory(dirname($uploadPath));

                if ($this->fileService->uploadFile($tmpName, $uploadPath)) {
                    $this->fileModel->createFile($filename, $directoryId);
                } else {
                    http_response_code(500);
                }
            }
        }
    }

    public function download()
    {
        $filename = $_GET['filename'] ?? '';

        $filePath = $this->fileService->getUploadDir() . basename($filename);

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "Ошибка: Файл не найден.";
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
