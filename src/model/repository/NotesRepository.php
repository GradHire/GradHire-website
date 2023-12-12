<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Notes;
use Exception;

class NotesRepository extends AbstractRepository
{
	/**
	 * @throws ServerErrorException
	 */
	public function create(array $dataObject): void
	{
		$id = self::Fetch("SELECT idnote FROM Notes WHERE idnote = (SELECT MAX(idnote) FROM Notes)");
		$id = $id["idnote"] + 1;
		$dataObject["idnote"] = $id;
		self::Execute("INSERT INTO " . $this->getNomTable() . " (" . implode(", ", $this->getNomColonnes()) . ") VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)", [
			$dataObject["idnote"],
			$dataObject["etudiant"],
			$dataObject["presenttuteur"],
			$dataObject["renduretard"],
			$dataObject["noterapport"],
			$dataObject["commentairerapport"],
			$dataObject["noteoral"],
			$dataObject["commentaireoral"],
			$dataObject["noterelation"],
			$dataObject["langage"],
			$dataObject["nouveau"],
			$dataObject["difficulte"],
			$dataObject["notedemarche"],
			$dataObject["noteresultat"],
			$dataObject["commentaireresultat"],
			$dataObject["recherche"],
			$dataObject["recontact"],
			$dataObject["idsoutenance"],
		]);
	}

	protected function getNomTable(): string
	{
		return "Notes";
	}

	protected function getNomColonnes(): array
	{
		return [
			"idnote",
			"etudiant",
			"presenttuteur",
			"renduretard",
			"noterapport",
			"commentairerapport",
			"noteoral",
			"commentaireoral",
			"noterelation",
			"langage",
			"nouveau",
			"difficulte",
			"notedemarche",
			"noteresultat",
			"commentaireresultat",
			"recherche",
			"recontact",
			"idsoutenance",
		];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getById(int $getSoutenance): ?Notes
	{
		$dataObjectFormatTableau = self::Fetch("SELECT * FROM " . $this->getNomTable() . " WHERE idsoutenance = ?", [$getSoutenance]);
		return $dataObjectFormatTableau ? $this->construireDepuisTableau($dataObjectFormatTableau) : null;
	}

	/**
	 * @throws Exception
	 */
	protected function construireDepuisTableau(array $dataObjectFormatTableau): Notes
	{
		return new Notes($dataObjectFormatTableau);
	}
}