<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\SimulationPstage;

class SimulationPstageRepository extends AbstractRepository
{
	/**
	 * @throws ServerErrorException
	 */
	public function getFullByNomFichier(string $nomFichier): ?AbstractDataObject
	{
		$result = self::FetchAllAssoc("SELECT * FROM SimulationPstage WHERE nomFichier = :nomFichier", [":nomFichier" => $nomFichier]);
		return $result ? $this->construireDepuisTableau($result[0]) : null;
	}

	protected function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject
	{
		return new SimulationPstage(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function create(mixed $nomFichier, mixed $idEtudiant, mixed $statut): void
	{
		self::Execute("INSERT INTO SimulationPstage(nomFichier,statut ,idEtudiant) VALUES (:nomFichier,:statut ,:idEtudiant)", [
			":nomFichier" => $nomFichier, ":idEtudiant" => $idEtudiant, ":statut" => $statut]);
	}

	public function getAll(): ?array
	{
		$result = self::FetchAllAssoc("SELECT * FROM SimulationPstage");
		if (!$result)
			return null;
		$dataObjects = [];
		foreach ($result as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
		return $dataObjects;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function updatevalide(mixed $id): void
	{
		self::Execute("UPDATE SimulationPstage SET statut = 'Validee' WHERE idSimulation = :id", [":id" => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function updaterefuse(mixed $id): void
	{
		self::Execute("UPDATE SimulationPstage SET statut = 'Refusee' WHERE idSimulation = :id", [":id" => $id]);
	}

	public function getByIdEtudiant(int $id)
	{
		$result = self::FetchAllAssoc("SELECT * FROM SimulationPstage WHERE idEtudiant = :id", [":id" => $id]);
		if (!$result)
			return null;
		$dataObjects = [];
		foreach ($result as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
		return $dataObjects;
	}

	protected function getNomTable(): string
	{
		return "SimulationPstage";
	}

	protected function getNomColonnes(): array
	{
		return ["idsimulation", "nomfichier", "statut", "idetudiant"];
	}
}