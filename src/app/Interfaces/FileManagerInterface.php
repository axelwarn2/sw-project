<?php

namespace App\Interfaces;

interface FileManagerInterface
{
    public function createDirectory(string $path): bool;
    public function uploadFile(string $tmpName, string $uploadPath): bool;
    public function deleteFile(string $filePath): bool;
    public function deleteDirectory(string $directoryPath): bool;
    public function getBaseUploadDir(): string;
}
