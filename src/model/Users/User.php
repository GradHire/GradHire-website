<?php

namespace app\src\model\Users;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;

abstract class User
{
    protected static string $view = '';
    protected static string $id_attributes = '';
    protected static string $create_function = '';
    protected int $id;
    protected array $attributes;

    public function __construct(array $attributes) {
        if (count($attributes) == 0) return;
        $this->attributes = $attributes;
        $this->id = $attributes["idUtilisateur"] ?? 0;  // Use a default value of 0
    }

    public static function find_by_id(string $id): null|static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idutilisateur = ?");
            $statement->execute([$id]);
            $user = $statement->fetch();
            if (is_null($user)) return null;
            return new static($user);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function find_by_attribute(string $value): null|static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE " . static::$id_attributes . " = ?");
            $statement->execute([$value]);
            $user = $statement->fetch();
            if (is_null($user) || $user === false) return null;
            return new static($user);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function save(array $values): int
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT " . static::$create_function . "(" . ltrim(str_repeat(",?", count($values)), ",") . ") FROM DUAL");
            $statement->execute($values);
            return $statement->fetchColumn();
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function update(array $values)
    {
        try {
            $paramBindings = implode(",", array_map(fn($attr) => "$attr = :param_$attr", array_keys($values)));
            $statement = Database::get_conn()->prepare("UPDATE " . static::$view . " SET $paramBindings WHERE idutilisateur=:param_idutilisateur");
            $values["idutilisateur"] = $this->id;
            foreach ($values as $key => $item)
                $statement->bindValue(":param_$key", $item);
            $statement->execute();
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function role(): string
    {
        return '';
    }

    abstract function full_name(): string;
}