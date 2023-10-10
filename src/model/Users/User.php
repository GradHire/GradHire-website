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
			if ($this->is_me()) Application::setUser(static::find_by_id(Application::getUser()->id()));
		} catch (\Exception $e) {
			print_r($e);
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

	public function attributes(): array
	{
		return $this->attributes;
	}

	public function role(): Roles|null
	{
		return null;
	}

	abstract function full_name(): string;
}