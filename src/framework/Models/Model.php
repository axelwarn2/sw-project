<?php

namespace Framework\Models;

use Framework\CDatabase;
use Framework\Validators\ModelValidator;

abstract class Model
{
    protected array $data;
    protected array $fillable = [];
    protected static string $table;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getItems(): array
    {
        $query = "SELECT * FROM " . static::$table;
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function getColumnById(string $column, int $id): string
    {
        $query = "SELECT {$column} FROM " . static::$table . " WHERE id = :id";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn();
    }

    protected function getColumn(string $column, array $criteria): string
    {
        $query = "SELECT {$column} FROM " . static::$table . " WHERE ";
        $conditions = [];
        $params = [];

        foreach ($criteria as $key => $value) {
            $conditions[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $query .= implode(" AND ", $conditions);
        $stmt = CDatabase::getInstanse()->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function delete(): bool
    {
        return static::deleteExec($this->getID());
    }

    public static function deleteIternal(int $id): bool
    {
        return static::deleteExec($id);
    }

    protected static function deleteExec(int $id): bool
    {
        $query = "DELETE FROM " . static::$table . " WHERE id = :id";
        $stmt = CDatabase::getInstanse()->connection->prepare($query);

        return $stmt->execute(['id' => $id]);
    }

    public function __call(string $name, array $args)
    {
        if (strpos($name, 'get') !== 0) {
            return null;
        }

        $fieldName = strtolower(substr($name, 3));

        return $this->data[$fieldName] ?? null;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (!in_array($name, $this->fillable)) {
            return;
        }

        $this->data[$name] = $value;
    }
}
