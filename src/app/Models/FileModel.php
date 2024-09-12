<?php

namespace App\Models;

use Framework\Models\Model;
use Framework\CDatabase;

class FileModel extends Model
{
    protected static string $table = 'files';

    public function getFiles()
    {
        $query = "SELECT * FROM " . static::$table;
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFileName($fileId)
    {
        $query = "SELECT filename FROM " . static::$table . " WHERE id = :id";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['id' => $fileId]);
        return $stmt->fetchColumn();
    }

    public function getFilePath($fileId)
    {
        $query = "SELECT path FROM " . static::$table . " WHERE id = :id";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['id' => $fileId]);
        return $stmt->fetchColumn();
    }

    public function getParentPath($directoryId)
    {
        $query = "SELECT path FROM directories WHERE id = :directoryId";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['directoryId' => $directoryId]);
        return $stmt->fetchColumn();
    }

    public function createFile($filename, $directoryId)
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

    public function deleteFile($fileId)
    {
        static::deleteIternal($fileId);
    }
}
