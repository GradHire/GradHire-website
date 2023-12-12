<?php


namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;

abstract class AbstractRepository
{
	/**
	 * @throws ServerErrorException
	 */
	protected static function FetchAllAssoc(string $sql, array $params = []): array|null
	{
		$statement = Database::get_conn()->prepare($sql);
		$statement->execute($params);
		$data = $statement->fetchAll(\PDO::FETCH_ASSOC);
		if (!$data) return null;
		return $data;
	}

	/**
	 * @throws ServerErrorException
	 */
	protected static function Execute(string $sql, array $params = []): void
	{
		$statement = Database::get_conn()->prepare($sql);
		$statement->execute($params);
	}

	/**
	 * @throws ServerErrorException
	 */
	protected static function FetchAll(string $sql, array $params = []): array|null
	{
		$statement = Database::get_conn()->prepare($sql);
		$statement->execute($params);
		$data = $statement->fetchAll();
		if (!$data) return null;
		return $data;
	}

	/**
	 * @throws ServerErrorException
	 */
	protected static function FetchAssoc(string $sql, array $params = []): array|null
	{
		$statement = Database::get_conn()->prepare($sql);
		$statement->execute($params);
		$data = $statement->fetch(\PDO::FETCH_ASSOC);
		if (!$data) return null;
		return $data;
	}

	/**
	 * @throws ServerErrorException
	 */
	protected static function Fetch(string $sql, array $params = []): array|null
	{
		$statement = Database::get_conn()->prepare($sql);
		$statement->execute($params);
		$data = $statement->fetch();
		if (!$data) return null;
		return $data;
	}

	/**
	 * @throws ServerErrorException
	 */
	protected static function CheckExist(string $sql, array $params = []): bool
	{
		return self::RowCount($sql, $params) > 0;
	}

	/**
	 * @throws ServerErrorException
	 */
	protected static function RowCount(string $sql, array $params = []): int
	{
		$statement = Database::get_conn()->prepare($sql);
		$statement->execute($params);
		return $statement->rowCount();
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getAll(): ?array
	{
		$sql = Database::get_conn()->query("SELECT * FROM " . $this->getNomTable());
		$dataObjects = [];
		foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
		return $dataObjects;
	}

	protected abstract function getNomTable(): string;

	protected abstract function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject;

	protected abstract function getNomColonnes(): array;
}
