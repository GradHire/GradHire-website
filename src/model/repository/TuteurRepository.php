<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Tuteur;

class TuteurRepository extends ProRepository
{
	protected static string $view = "TuteurVue";
	protected static string $update_function = "updateTuteur";


	public function role(): Roles
	{
		return Roles::Tutor;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByIdFull($idutilisateur): ?Tuteur
	{
		$resultat = self::FetchAssoc("SELECT * FROM " . self::$view . " WHERE idUtilisateur = ?", [$idutilisateur]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	protected
	function construireDepuisTableau(array $dataObjectFormatTableau): Tuteur
	{
		return new Tuteur(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function addTuteur($idUtilisateur, $idOffre, $idEtudiant): void
	{
		self::Execute("UPDATE Supervise SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant", [
			'idUtilisateur' => $idUtilisateur,
			'idOffre' => $idOffre,
			'idEtudiant' => $idEtudiant,
		]);
		self::Execute("UPDATE Staff SET role = 'tuteurprof' WHERE idUtilisateur = :idUtilisateur AND role = 'enseignant'", [
			'idUtilisateur' => $idUtilisateur,
		]);
		self::Execute("UPDATE Postuler SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre", [
			'idUtilisateur' => $idEtudiant,
			'idOffre' => $idOffre,
		]);
		$this->refuserTuteur($idUtilisateur, $idOffre, $idEtudiant);
		self::Execute("UPDATE Postuler SET statut = 'refusee' WHERE idUtilisateur != :idUtilisateur AND idOffre = :idOffre", [
			'idUtilisateur' => $idEtudiant,
			'idOffre' => $idOffre,
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function refuserTuteur(int $getIdutilisateur, mixed $idOffre, $idEtudiant): void
	{
		self::Execute("UPDATE Supervise SET Statut = 'refusee' WHERE idUtilisateur!=:idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant", [
			'idUtilisateur' => $getIdutilisateur,
			'idOffre' => $idOffre,
			'idEtudiant' => $idEtudiant,
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getIfTuteurAlreadyExist($idUtilisateur, $idOffre, $idEtudiant): bool
	{
		$resultat = self::FetchAssoc("SELECT * FROM Supervise s JOIN Staff st ON st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND role = 'tuteurprof' AND statut = 'validee'", [
			'idUtilisateur' => $idUtilisateur,
			'idOffre' => $idOffre,
			'idEtudiant' => $idEtudiant
		]);
		if ($resultat == null || $resultat["statut"] == "en attente" || $resultat["statut"] == "refusee")
			return false;
		return true;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function annulerTuteur(int $iduser, int $idOffre, int $idetudiant): void
	{
		$resultat = self::FetchAssoc("SELECT COUNT(*) as nbfoistuteur FROM Supervise s JOIN Staff st on st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND statut = 'validee'", [
			'idUtilisateur' => $iduser,
		]);
		if ($resultat["nbfoistuteur"] < 1)
			self::Execute("UPDATE Staff SET role = 'enseignant' WHERE idUtilisateur = :idUtilisateur", [
				'idUtilisateur' => $iduser,
			]);
		self::Execute("UPDATE Supervise SET Statut = 'en attente' WHERE idOffre = :idOffre AND idEtudiant = :idEtudiant", [
			'idOffre' => $idOffre,
			'idEtudiant' => $idetudiant
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function seProposerProf(int $idutilisateur, int $idOffre, int $idetudiant): void
	{
		self::Execute("INSERT INTO Supervise VALUES (?,?,?,'en attente',null);", [
			$idutilisateur,
			$idOffre,
			$idetudiant
		]);
		self::Execute("UPDATE Postuler SET Statut = 'en attente tuteur entreprise' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre", [
			'idUtilisateur' => $idetudiant,
			'idOffre' => $idOffre
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function seDeProposerProf(int $idUtilisateur, mixed $idOffre, $idEtudiant)
	{
		self::Execute("DELETE FROM Supervise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant", [
			'idUtilisateur' => $idUtilisateur,
			'idOffre' => $idOffre,
			'idEtudiant' => $idEtudiant
		]);
		self::Execute("UPDATE Postuler SET Statut = 'en attente tuteur entreprise' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre", [
			'idUtilisateur' => $idEtudiant,
			'idOffre' => $idOffre
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getIdOffreByIdEtuAndIdOffre(int $idetudiant, int $idoffre)
	{
		$resultat = self::FetchAssoc("SELECT idOffre FROM Supervise WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre", [
			'idEtudiant' => $idetudiant,
			'idOffre' => $idoffre
		]);
		return $resultat["idOffre"];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getNomTuteurByIdEtudiantAndIdOffre(int $idetudiant, int $idoffre): ?string
	{
		$resultat = self::FetchAssoc("SELECT nom, prenom FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre", [
			'idEtudiant' => $idetudiant,
			'idOffre' => $idoffre
		]);
		return $resultat["prenom"] . " " . $resultat["nom"];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getTuteurByIdEtudiantAndIdOffre(int $idetudiant, int $idoffre): ?array
	{
		return self::FetchAssoc("SELECT * FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre", [
			'idEtudiant' => $idetudiant,
			'idOffre' => $idoffre
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function assigneCommeTuteurEntreprise(mixed $idUtilisateur, mixed $idOffre, mixed $idEtudiant, mixed $idTuteurEntreprise): void
	{
		self::Execute("UPDATE Supervise SET idTuteurEntreprise = :idTuteurEntreprise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant", [
			'idUtilisateur' => $idUtilisateur,
			'idOffre' => $idOffre,
			'idEtudiant' => $idEtudiant,
			'idTuteurEntreprise' => $idTuteurEntreprise
		]);
		self::Execute("UPDATE Postuler SET statut = 'en attente responsable' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre", [
			'idUtilisateur' => $idEtudiant,
			'idOffre' => $idOffre,
		]);
	}

	protected
	function getNomColonnes(): array
	{
		return [
			"idutilisateur",
			"email",
			"nom",
			"numtelephone",
			"bio",
			"archiver",
			"prenom",
			"fonction",
			"identreprise"
		];
	}

	protected
	function getNomTable(): string
	{
		return "TuteurVue";
	}
}