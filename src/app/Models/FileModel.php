<?php

namespace App\Models;

use Framework\Models\Model;
use Framework\CDatabase;
class FileModel extends Model
{
    protected static string $table = 'files';

    public function getFiles(): array
    {
        $query = "SELECT * FROM " . static::$table;
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
