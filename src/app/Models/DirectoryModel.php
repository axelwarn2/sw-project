<?php

namespace App\Models;

use Framework\Models\Model;
use Framework\CDatabase;

class DirectoryModel extends Model
{
    protected static string $table = 'directories';

    private function getParentPath($parentId)
    {
        if ($parentId) {
            $query = "SELECT path FROM " . static::$table . " WHERE id = :id";
            $stmt = CDatabase::getInstanse()->connection->prepare($query);
            $stmt->execute(['id' => $parentId]);
            return $stmt->fetchColumn();
        }

        return '';
    }

    public function getDirectories(): array
    {
        $query = "SELECT * FROM " . static::$table . " ORDER BY path";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createDirectory($name, $parentId)
    {
        $path = $this->getParentPath($parentId) . $name;

        if ($parentId) {
            $query = "INSERT INTO " . static::$table . " (name, parent_id, path) VALUES (:name, :parent_id, :path)";
            $stmt = CDatabase::getInstanse()->connection->prepare($query);
            $stmt->execute(['name' => $name, 'parent_id' => $parentId, 'path' => $path]); 
        } else {
            $query = "INSERT INTO " . static::$table . " (name, parent_id, path) VALUES (:name, :parent_id, :path)";
            $stmt = CDatabase::getInstanse()->connection->prepare($query);
            $stmt->execute(['name' => $name, 'parent_id' => null, 'path' => $path]); 
        }   
    }

    public function deleteDirectory($directoryId)
    {
        $query = "DELETE FROM files WHERE directory_id = :directory_id";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['directory_id' => $directoryId]);

        $query = "SELECT id FROM " . static::$table . " WHERE parent_id = :parent_id";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['parent_id' => $directoryId]);
        $childDirectories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($childDirectories as $childDirectory) {
            $this->deleteDirectory($childDirectory['id']);
        }

        static::deleteIternal($directoryId);
    }
}
