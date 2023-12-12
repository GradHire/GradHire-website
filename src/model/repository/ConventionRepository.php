<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Convention;

class ConventionRepository extends AbstractRepository
{
	protected static string $table = "Convention";

	/**
	 * @throws ServerErrorException
	 */
	public static function getStudentId(int $conventionId): int
	{
		$data = self::Fetch("SELECT idetudiant FROM hirchytsd.\"conventionValideVue\" WHERE numconvention=?", [$conventionId]);
		return $data["idetudiant"];
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getAddress(int $conventionId): string|null
	{
		$data = self::Fetch("SELECT e.adresse, vi.codepostal, vi.nomville FROM entreprise e JOIN offre o ON o.idutilisateur = e.idutilisateur JOIN convention c ON c.idoffre = o.idoffre JOIN ville vi ON e.idville = vi.idville WHERE c.numconvention=?", [$conventionId]);
		if (!$data) return null;
		return $data["adresse"] . ", " . $data["codepostal"] . " " . $data["nomville"];
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getIdByStudent(int $id): int|null
	{
		$num = self::Fetch("SELECT numconvention FROM convention WHERE idutilisateur = :id", ['id' => $id]);
		if (!$num) return null;
		return $num["numconvention"];
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function exist(int $numConvention): bool
	{
		return self::Fetch("SELECT numconvention FROM convention WHERE numconvention = :numConvention", ['numConvention' => $numConvention]) !== null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getByNumConvention(int $numConvention): array|null
	{
		return self::Fetch("SELECT * FROM convention WHERE numconvention = :numConvention", ['numConvention' => $numConvention]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getConventionXOffreById(mixed $id): array
	{
		return self::Fetch("SELECT * FROM convention WHERE numconvention = :id", ['id' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function validerPedagogiquement(int $id): void
	{
		self::Execute("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 1 WHERE numconvention = :id", ['id' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getById(int $id): ?array
	{
		return self::Fetch("SELECT * FROM " . static::$table . " WHERE numconvention = :id", ['id' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function unvalidatePedagogiquement(mixed $id): void
	{
		self::Execute("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 0 WHERE numconvention = :id", ['id' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function unvalidate(mixed $id): void
	{
		self::Execute("UPDATE " . static::$table . " SET conventionvalidee = 0 WHERE numconvention = :id", ['id' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getIfIdTuteurs(int $numconvention, int $idtuteur): bool
	{
		$data = self::Fetch("SELECT idtuteurprof, idtuteurentreprise FROM \"conventionValideVue\" WHERE numconvention = :numconvention", ['numconvention' => $numconvention]);
		return $data["idtuteurprof"] == $idtuteur || $data["idtuteurentreprise"] == $idtuteur;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getInformationByNumConvention(int $numconvention): array
	{
		return self::Fetch("SELECT * FROM \"conventionValideVue\" WHERE numconvention = :numconvention", ['numconvention' => $numconvention]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function validate(mixed $id): void
	{
		self::Execute("UPDATE " . static::$table . " SET conventionvalidee = 1 WHERE numconvention = :id", ['id' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getAll(): array
	{
		$data = self::FetchAll("SELECT * FROM " . static::$table);
		$conventions = [];
		foreach ($data as $row)
			$conventions[] = $this->construireDepuisTableau($row);
		return $conventions;
	}

	public function construireDepuisTableau(array $dataObjectFormatTableau): Convention
	{
		return new Convention(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByIdOffreAndIdUser(int $idOffre, int $idUser): ?Convention
	{
		$data = self::Fetch("SELECT * FROM " . static::$table . " WHERE idoffre = :idOffre AND idutilisateur = :idUser", ['idOffre' => $idOffre, 'idUser' => $idUser]);
		if (!$data) return null;
		return $this->construireDepuisTableau($data);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function checkIfConventionsValideePedagogiquement(int $idConvention): bool
	{
		$data = self::Fetch("SELECT conventionvalideepedagogiquement FROM " . static::$table . " WHERE numconvention = :idConvention", ['idConvention' => $idConvention]);
		if (!$data) return false;
		return (bool)$data["conventionvalideepedagogiquement"];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getPourcentageEtudiantsConventionCetteAnnee(): false|array
	{
		return self::Fetch("SELECT * FROM pourcentage_etudiants_convention_cette_annee;");
	}


	protected function getNomTable(): string
	{
		return static::$table;
	}

	protected function getNomColonnes(): array
	{
		return [
			"numconvention",
			"origineconvention",
			"conventionvalidee",
			"conventionvalideepedagogiquement",
			"datemodification",
			"datecreation",
			"idsignataire",
			"idinterruption",
			"idutilisateur",
			"idoffre",
			"commentaire"
		];
	}
}