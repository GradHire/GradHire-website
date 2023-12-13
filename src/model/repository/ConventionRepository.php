<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Convention;

class ConventionRepository extends AbstractRepository
{
	protected static string $table = "Convention";

	/**
	 * @throws ServerErrorException
	 */
	public static function getStudentId(int $conventionId): int|null
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT idetudiant FROM hirchytsd.\"conventionValideVue\" WHERE numconvention=?");
			$statement->execute([$conventionId]);
			$data = $statement->fetch();
			if (!$data) return null;
			return $data["idetudiant"];
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la récupération de l'id de l'étudiant", 500, $e);
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getAddress(int $conventionId): string|null
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT e.adresse, vi.codepostal, vi.nomville FROM entreprise e JOIN offre o ON o.idutilisateur = e.idutilisateur JOIN convention c ON c.idoffre = o.idoffre JOIN ville vi ON e.idville = vi.idville WHERE c.numconvention=?");
			$statement->execute([$conventionId]);
			$data = $statement->fetch();
			if (!$data) return null;
			return $data["adresse"] . ", " . $data["codepostal"] . " " . $data["nomville"];
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getIdByStudent(int $id): int|null
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT numconvention FROM convention WHERE idutilisateur=?");
			$statement->execute([$id]);
			$num = $statement->fetch();
			if (!$num) return null;
			return $num["numconvention"];
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function exist(int $numConvention): bool
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT numconvention FROM convention WHERE numconvention=?");
			$statement->execute([$numConvention]);
			$data = $statement->fetch();
			return $data !== null;
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getByNumConvention(int $numConvention): array|null
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT * FROM convention WHERE numconvention=?");
			$statement->execute([$numConvention]);
			return $statement->fetch();
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	public static function getConventionXOffreById(mixed $id)
	{
		$statement = Database::get_conn()->prepare("SELECT c.idutilisateur as idetudiant, c.idoffre, o.idutilisateur, o.sujet FROM convention c JOIN Offre o ON c.idoffre = o.idoffre WHERE numconvention = :id");
		$statement->execute([
			'id' => $id
		]);
		$data = $statement->fetch();
		return $data;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function validerPedagogiquement(int $id): void
	{
		try {
			$statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 1 WHERE numconvention = :id");
			$statement->bindParam(":id", $id);
			$statement->execute();
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la validation pédagogique de la convention", 500, $e);
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getById(int $id): ?array
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table . " WHERE numconvention = :id");
			$statement->bindParam(":id", $id);
			$statement->execute();
			$data = $statement->fetch();
			if (!$data) return null;
			return $data;
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function unvalidatePedagogiquement(mixed $id): void
	{
		try {
			$statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 0 WHERE numconvention = :id");
			$statement->bindParam(":id", $id);
			$statement->execute();
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de l'archivage pédagogique de la convention", 500, $e);
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function unvalidate(mixed $id): void
	{
		try {
			$statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalidee = 0 WHERE numconvention = :id");
			$statement->bindParam(":id", $id);
			$statement->execute();
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de l'archivage de la convention", 500, $e);
		}
	}

	public static function getIfIdTuteurs(int $numconvention, int $idtuteur): bool
	{
		$statement = Database::get_conn()->prepare("SELECT idtuteurprof, idtuteurentreprise FROM \"conventionValideVue\" WHERE numconvention = :numconvention");
		$statement->execute([
			'numconvention' => $numconvention
		]);
		$data = $statement->fetch();
		if ($data["idtuteurprof"] == $idtuteur || $data["idtuteurentreprise"] == $idtuteur)
			return true;
		else
			return false;
	}

	public static function getInformationByNumConvention(int $numconvention): array
	{
		$statement = Database::get_conn()->prepare("SELECT * FROM \"conventionValideVue\" WHERE numconvention = :numconvention");
		$statement->execute([
			'numconvention' => $numconvention
		]);
		$data = $statement->fetch();
		return $data;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function validate(mixed $id): void
	{
		try {
			$statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalidee = 1 WHERE numconvention = :id");
			$statement->bindParam(":id", $id);
			$statement->execute();
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la validation de la convention", 500, $e);
		}
	}

    public static function imOneOfTheTutor(int $id, $numconvention): bool
    {
        $statement = Database::get_conn()->prepare("SELECT idtuteurprof, idtuteurentreprise FROM \"conventionValideVue\" WHERE numconvention = :numconvention");
        $statement->execute([
            'numconvention' => $numconvention
        ]);
        $data = $statement->fetch();
        if ($data["idtuteurprof"] == $id || $data["idtuteurentreprise"] == $id)
            return true;
        else
            return false;
    }

    /**
	 * @throws ServerErrorException
	 */
	public function getAll(): array
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table);
			$statement->execute();
			$data = $statement->fetchAll();
			$conventions = [];
			foreach ($data as $row) {
				$conventions[] = $this->construireDepuisTableau($row);
			}
			return $conventions;
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la récupération des conventions", 500, $e);
		}
	}

	public function construireDepuisTableau(array $dataObjectFormatTableau): Convention
	{
		return new Convention(
			$dataObjectFormatTableau
		);
	}

	public function getByIdOffreAndIdUser(int $idOffre, int $idUser): ?Convention
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table . " WHERE idoffre = :idOffre AND idutilisateur = :idUser");
			$statement->bindParam(":idOffre", $idOffre);
			$statement->bindParam(":idUser", $idUser);
			$statement->execute();
			$data = $statement->fetch();
			if (!$data) return null;
			return $this->construireDepuisTableau($data);
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public function checkIfConventionsValideePedagogiquement(int $idConvention): bool
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT conventionvalideepedagogiquement FROM " . static::$table . " WHERE numconvention = :idConvention");
			$statement->bindParam(":idConvention", $idConvention);
			$statement->execute();
			$data = $statement->fetch();
			if (!$data) return false;
			return (bool)$data["conventionvalideepedagogiquement"];
		} catch (\Exception $e) {
			throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
		}
	}

	public function getPourcentageEtudiantsConventionCetteAnnee(): false|array
	{
		return Database::get_conn()->query("SELECT * FROM pourcentage_etudiants_convention_cette_annee_cache;")->fetch();
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