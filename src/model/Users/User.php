<?php

namespace app\src\model\Users;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;

abstract class User
{
    protected static string $view = '';
    protected static string $id_attributes = '';
    protected static string $create_function = '';

    protected static string $update_function = '';
    protected int $id;
    protected array $attributes;

    public function __construct(array $attributes)
    {
        if (count($attributes) == 0) return;
        $this->attributes = $attributes;
        $this->id = $attributes["idutilisateur"] ?? 0;
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
    public static function save(array $values): static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT " . static::$create_function . "(" . ltrim(str_repeat(",?", count($values)), ",") . ") FROM DUAL");
            $statement->execute($values);
            return static::find_by_id(intval($statement->fetchColumn()));
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public static function find_by_id(string $id): null|static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idutilisateur = ?");
            $statement->execute([$id]);
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
    public function update(array $values): void
    {
        try {
            $statement = Database::get_conn()->prepare("CALL " . static::$update_function . "(" . $this->id . ", " . ltrim(str_repeat(",?", count($values)), ",") . ")");
            $statement->execute(array_values($values));
            if ($this->is_me()) {
                $this->refresh();
                Application::setUser($this);
            }
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function is_me(): bool
    {
        if (Application::isGuest()) return false;
        if (is_null(Application::getUser())) return false;
        return Application::getUser()->id() === $this->id;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function refresh(): void
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idutilisateur = ?");
            $statement->execute([$this->id]);
            $user = $statement->fetch();
            if (is_null($user)) throw new ServerErrorException();
            $this->attributes = $user;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function role(): Roles|null
    {
        return null;
    }

    public function get_picture(): string
    {
        if (file_exists("./pictures/" . $this->id . ".jpg")) return "/pictures/" . $this->id . ".jpg";
        return "https://as2.ftcdn.net/v2/jpg/00/64/67/63/1000_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg";
    }

    abstract function full_name(): string;
}