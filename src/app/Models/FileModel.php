<?php

namespace App\Models;

use Framework\Models\Model;
use Framework\CDatabase;

class FileModel extends Model
{
    protected static string $table = 'files';

    public function getFiles(): array
    {
        return $this->getItems();
    }

    public function getFileName(int $fileId): string
    {
        return $this->getColumnById('filename', $fileId);
    }

    public function getFilePath(int $fileId): string
    {
        return $this->getColumnById('path', $fileId);
    }

    public function getParentPath(int $directoryId): string
    {
        $query = "SELECT path FROM directories WHERE id = :directoryId";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['directoryId' => $directoryId]);
        return $stmt->fetchColumn();
    }

    public function createFile(string $filename, int $directoryId): void
    {
        $directoryPath = 'uploads' . $this->getParentPath($directoryId);
        $path = $directoryPath . '/' . $filename;
        $query = "INSERT INTO " . static::$table . " (filename, directory_id, path) VALUES (:filename, :directory_id, :path)";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute([
            ':filename' => $filename,
            ':directory_id' => $directoryId,
            ':path' => $path,
        ]);
    }

    public function deleteFile(int $fileId): void
    {
        static::deleteIternal($fileId);
    }
}
