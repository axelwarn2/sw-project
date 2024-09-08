<?php

namespace App\Controllers;

use App\Models\FileModel;

class FileController
{
    private FileModel $fileModel;
    private string $uploadDir;

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

            if (!empty($filename) && $directoryId) {
                $uploadFile = $this->uploadDir . $filename;

                if (move_uploaded_file($tmpName, $uploadFile)) {
                    $this->fileModel->createFile($filename, $directoryId);
                } else {
                    throw new \Exception("Не удалось загрузить файл", 500);
                }
            }
        }
    }

    public function delete()
    {
        $fileId = $_POST['itemId'] ?? null;

        $this->fileModel->deleteFile($fileId);
    }
}
