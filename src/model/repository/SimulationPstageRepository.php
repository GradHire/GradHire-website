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
	public static function getStudentId(int $conventionId): int|null
	{
		$data = self::Fetch("SELECT idetudiant FROM hirchytsd.\"conventionValideVue\" WHERE numconvention=?", [$conventionId]);
		return $data ? $data["idetudiant"] : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getNomById(int $id): ?string
	{
		$sql = "SELECT nomFichier FROM SimulationPstage WHERE idSimulation = :id";
		$result = self::FetchAllAssoc($sql, ['id' => $id]);
		return $result ? $result[0]["nomfichier"] : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getFullByNomFichier(string $nomFichier): ?AbstractDataObject
	{
		$sql = "SELECT * FROM SimulationPstage WHERE nomFichier = :nomFichier";
		$result = self::FetchAllAssoc($sql, ['nomFichier' => $nomFichier]);
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
			"nomFichier" => $nomFichier,
			"idEtudiant" => $idEtudiant,
			"statut" => $statut
		]);
	}

	public function getAll(): ?array
	{
		$result = self::FetchAllAssoc("Select * from SimulationPstage");
		if (!$result) return null;
		$dataObjects = [];
		foreach ($result as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
		return $dataObjects;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function updatevalide(mixed $id): void
	{
		self::Execute("UPDATE SimulationPstage SET statut = 'Validee' WHERE idSimulation = :id", [
			"id" => $id
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function updaterefuse(mixed $id): void
	{
		self::Execute("UPDATE SimulationPstage SET statut = 'Refusee' WHERE idSimulation = :id", [
			"id" => $id
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByIdEtudiant(int $id): ?array
	{
		$sql = "SELECT * FROM SimulationPstage WHERE idEtudiant = :id";
		$result = self::FetchAllAssoc($sql, ['id' => $id]);
		if (!$result) return null;
		$dataObjects = [];
		foreach ($result as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
		return $dataObjects;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function updateMotif(mixed $id, mixed $motif): void
	{
		self::Execute("UPDATE SimulationPstage SET motif = :motif WHERE idSimulation = :id", [
			"id" => $id,
			"motif" => $motif
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getMotifById(mixed $id)
	{
		$sql = "SELECT motif FROM SimulationPstage WHERE idSimulation = :id";
		$result = self::FetchAllAssoc($sql, ['id' => $id]);
		return $result ? $result[0]["motif"] : null;
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
