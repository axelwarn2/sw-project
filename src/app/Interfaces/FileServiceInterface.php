<?php

namespace App\Interfaces;

interface FileServiceInterface
{
    public function uploadFile(string $tmpName, string $uploadPath): bool;
    public function deleteFile(string $filePath): bool;
    public function createDirectory(string $fullPath): bool;
    public function deleteDirectory(string $directoryPath): bool;
}
