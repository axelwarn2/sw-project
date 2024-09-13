<?php

namespace App\Interfaces;

interface FileServiceInterface
{
    public function uploadFile($tmpName, $uploadPath);
    public function deleteFile($filePath);
    public function createDirectory($fullPath);
    public function deleteDirectory($directoryPath);
}
