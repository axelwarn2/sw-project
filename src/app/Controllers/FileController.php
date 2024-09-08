<?php

namespace App\Controllers;

use App\Models\FileModel;

class FileController
{
    private FileModel $fileModel;
    private string $uploadDir;
    private $maxFileSize = 20 * 1024 * 1024;
    private $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf'];

    public function __construct(FileModel $fileModel)
    {
        $this->fileModel = $fileModel;
        $this->uploadDir = dirname(__DIR__, 2) . '/uploads/';
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

            if (!empty($filename) && $directoryId) {
                $uploadFile = $this->uploadDir . $filename;

                if (move_uploaded_file($tmpName, $uploadFile)) {
                    $this->fileModel->createFile($filename, $directoryId);
                } else {
                    http_response_code(500);
                }
            }
        }
    }
}
