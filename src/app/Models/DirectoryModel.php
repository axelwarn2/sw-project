<?php

namespace App\Models;

use Framework\Models\Model;
use Framework\CDatabase;
class DirectoryModel extends Model
{
    protected static string $table = 'directories';

    public function getDirectories(): array
    {
        $query = "SELECT * FROM " . static::$table . " ORDER BY path";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
