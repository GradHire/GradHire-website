<?php

namespace app\src\core\db;

use app\src\core\exception\ServerErrorException;
use app\src\model\Model;

abstract class DbModel extends Model
{

    public static function primaryKey(): string
    {
        return 'id';
    }

    /**
     * @param array $where
     * @return static|null
     * @throws ServerErrorException
     */
    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    abstract public static function tableName(): string;

    /**
     * @throws ServerErrorException
     */
    public static function prepare($sql): \PDOStatement
    {
        return Database::get_conn()->prepare($sql);
    }

    /**
     * @throws ServerErrorException
     */
    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") 
                VALUES (" . implode(",", $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }
}