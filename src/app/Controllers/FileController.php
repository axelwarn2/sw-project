<?php

namespace App\Controllers;

use App\Models\FileModel;
use App\Services\FileService;
use App\Responses\Response;

class FileController
{
    private FileModel $fileModel;
    private FileService $fileService;
    private int $maxFileSize = 20 * 1024 * 1024;
    private array $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf'];

    
    public function __construct(FileModel $fileModel, FileService $fileService)
    {
        $this->fileModel = $fileModel;
        $this->fileService = $fileService;
    }

    public function createFile(): array
    {
        if (isset($_FILES['file'])) {
            $directoryId = $_POST['directoryId'] ?? null;
            $filename = $_FILES['file']['name'];
            $tmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

            if ($fileSize > $this->maxFileSize) {
                return ['error' => "Превышен максимальный размер файла", 'statusCode' => 413];
            }

            if (!in_array($fileExtension, $this->allowedFileExtensions)) {
                return ['error' => "Недопустимое расширение файла", 'statusCode' => 400];
            }

            if ($directoryId) {
                $directoryPath = $this->fileModel->getParentPath($directoryId);
                $uploadPath = $directoryPath . '/' . $filename;

                $this->fileService->createDirectory(dirname($uploadPath));

                if ($this->fileService->uploadFile($tmpName, $uploadPath)) {
                    $this->fileModel->createFile($filename, $directoryId);
                    return [
                        'view' => 'index', 
                        'directoryTree' => $this->fileModel->getDirectoryTree(),
                    ];
                } else {
                    return ['error' => "Ошибка загрузки файла", 'statusCode' => 500];
                }
            }
        }

        return ['view' => 'index', 'directoryTree' => $this->fileModel->getDirectoryTree()];
    }

    public function download(): array
    {
        $filename = $_GET['filename'] ?? '';

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $filename;

        if (!file_exists($filePath)) {
            return ['error' => "Файл не найден", 'statusCode' => 404];
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
    }
}
