<?php

namespace App\Models;

use Framework\Models\Model;
use Framework\CDatabase;

class DirectoryModel extends Model
{
    protected static string $table = 'directories';

    public function getDirectoryName(int $directoryId): string
    {
        return $this->getColumnById('name', $directoryId);
    }

    public function getDirectoryPath(?string $directoryId): ?string
    {
        return $this->getColumn('path', ['id' => $directoryId]);
    }

    private function getParentPath(?string $parentId): string
    {
        return $parentId ? $this->getColumn('path', ['id' => $parentId]) : "";
    }

    public function getDirectories(): array
    {
        return $this->getItems();
    }

    public function createDirectory(string $name, ?string $parentId): void
    {
        $path = $this->getParentPath($parentId) . '/' . $name;

        $query = "INSERT INTO " . static::$table . " (name, parent_id, path) VALUES (:name, :parent_id, :path)";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['name' => $name, 'parent_id' => $parentId ? $parentId : null, 'path' => $path]);
    }

    public function deleteDirectory(int $directoryId): void
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
