<?php

namespace App\Interfaces;

interface FileManagerInterface
{
    public function createDirectory($path);
    public function uploadFile($tmpName, $uploadPath);
    public function deleteFile($filePath);
    public function deleteDirectory($directoryPath);
    public function getBaseUploadDir();
}
