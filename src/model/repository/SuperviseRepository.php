<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Supervise;

class SuperviseRepository extends AbstractRepository
{
	/**
	 * @throws ServerErrorException
	 */
	public static function getByConvention(int $numConvention): Supervise|null
	{
		$data = self::Fetch("SELECT s.* FROM supervise s
                JOIN convention c ON c.idoffre = s.idoffre
                WHERE c.numconvention=?", [$numConvention]);
		if (!$data) return null;
		return new Supervise($data);
	}

	protected function getNomTable(): string
	{
		return "supervise";
	}

	protected function construireDepuisTableau(array $dataObjectFormatTableau): Supervise
	{
		return new Supervise($dataObjectFormatTableau);
	}

	protected function getNomColonnes(): array
	{
		return [];
	}
}