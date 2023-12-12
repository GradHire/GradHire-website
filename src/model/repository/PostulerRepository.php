<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Postuler;

class PostulerRepository extends AbstractRepository
{

	/**
	 * @throws ServerErrorException
	 */
	private string $nomTable = "PostulerVue";

	/**
	 * @throws ServerErrorException
	 */
	public static function postuler($id): void
	{
		self::Execute("INSERT INTO Postuler (idoffre, idUtilisateur, dates) VALUES (?,?,?)", [
			$id,
			Application::getUser()->id(),
			date("Y-m-d H:i:s")
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getById($idOffre, $idUtilisateur): ?Postuler
	{
		$resultat = self::FetchAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idOffre=:idOffre AND idUtilisateur=:idUtilisateur", [
			'idOffre' => $idOffre,
			'idUtilisateur' => $idUtilisateur
		]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	protected function construireDepuisTableau(array $dataObjectFormatTableau): Postuler
	{
		return new Postuler(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByIdEntreprise($identreprise): ?array
	{
		return self::FetchAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE identreprise= :id AND statut::text='validee' OR statut::text='refusee'", ['id' => $identreprise]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getCandidaturesAttenteEntreprise($identreprise): ?array
	{
		return self::FetchAllAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE identreprise= :id AND statut::text LIKE 'en attente%'", ['id' => $identreprise]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getCandidaturesAttenteEtudiant($identreprise): ?array
	{
		return self::FetchAllAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idUtilisateur= :id AND statut::text LIKE 'en attente%'", ['id' => $identreprise]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByIdEtudiant($idEtudiant): ?array
	{
		return self::FetchAllAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idUtilisateur= :id AND statut::text='validee' OR statut::text='refusee'", ['id' => $idEtudiant]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function setStatutPostuler(int $idutilisateur, int $idoffre, string $etat): void
	{
		self::Execute("UPDATE $this->nomTable SET statut=:etat WHERE idUtilisateur=:idutilisateur AND idOffre=:idoffre", [
			'etat' => $etat,
			'idutilisateur' => $idutilisateur,
			'idoffre' => $idoffre
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByStatementValideeOrRefusee(): array
	{
		return self::FetchAllAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut::text='validee' OR statut::text='refusee'");
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByStatementAttente(): array
	{
		return self::FetchAllAssoc("SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut::text LIKE 'en attente%'");
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getAll(): ?array
	{
		return self::FetchAllAssoc("SELECT * FROM $this->nomTable;");
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getStatsCandidaturesParMois(): array
	{
		return self::FetchAllAssoc("SELECT * FROM candidatures_par_mois_cache;") ?? [];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getIfSuivi(int $idUtilisateur, $idetu, $idoffre): bool
	{
		$data = self::Fetch("SELECT idUtilisateur FROM Supervise WHERE idutilisateur = :idutilisateur AND idetudiant = :idetudiant AND idoffre = :idoffre", [
			'idutilisateur' => $idUtilisateur,
			'idetudiant' => $idetu,
			'idoffre' => $idoffre
		]);
		if ($data == null) return false;
		return true;
	}

	public function getByIdOffre(mixed $idOffre): ?array
	{
		$sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idOffre=:idOffre";
		$requete = Database::get_conn()->prepare($sql);
		$requete->execute(['idOffre' => $idOffre]);
		$requete->setFetchMode(\PDO::FETCH_ASSOC);
		$resultat = [];
		foreach ($requete as $item) {
			$resultat[] = $this->construireDepuisTableau($item);
		}
		return $resultat;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getSiTuteurPostuler(?int $getIdUtilisateur, ?int $getIdOffre): bool
	{
		$resultat = self::FetchAssoc("SELECT idUtilisateur FROM Supervise WHERE idEtudiant=:idUtilisateur AND idOffre=:idOffre", [
			'idUtilisateur' => $getIdUtilisateur,
			'idOffre' => $getIdOffre
		]);
		return $resultat !== null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getTuteurByIdOffre(mixed $idOffre): ?array
	{
		return self::FetchAllAssoc("SELECT * FROM Supervise WHERE idOffre=:idOffre", ['idOffre' => $idOffre]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function refuserCandidature(int $idutilisateur, mixed $idOffre): void
	{
		self::Execute("UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur", [
			'idoffre' => $idOffre,
			'idutilisateur' => $idutilisateur
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function validerCandidatureEtudiant(mixed $idEtudiant, mixed $idOffre): void
	{
		self::Execute("UPDATE Postuler SET statut = 'en attente tuteur prof' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur", [
			'idoffre' => $idOffre,
			'idutilisateur' => $idEtudiant
		]);
		self::Execute("UPDATE Postuler SET statut = 'refusee' WHERE idOffre!=:idoffre AND idUtilisateur=:idutilisateur", [
			'idoffre' => $idOffre,
			'idutilisateur' => $idEtudiant
		]);
		self::Execute("UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur!=:idutilisateur", [
			'idoffre' => $idOffre,
			'idutilisateur' => $idEtudiant
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function validerCandidatureEntreprise(int $idUtilisateur, int $idOffre): void
	{
		self::Execute("UPDATE Postuler SET statut = 'en attente etudiant' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur", [
			'idoffre' => $idOffre,
			'idutilisateur' => $idUtilisateur
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByStatementAttenteTuteur(): array
	{
		return self::FetchAllAssoc("SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE CAST(statut as TEXT) = 'en attente tuteur prof' OR CAST(statut as TEXT) = 'en attente responsable' OR CAST(statut as TEXT) = 'en attente tuteur entreprise'");
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByStatementTuteur(int $idutilisateur, string $string)
	{
		if ($string == 'validee') {
			return self::FetchAllAssoc("SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur, su.idUtilisateur as idTuteur,idEntreprise,p.statut FROM $this->nomTable p JOIN Supervise su ON su.idOffre=p.idOffre WHERE su.statut::text = 'validee' AND su.idutilisateur=:idutilisateur AND CAST(p.statut AS TEXT) = CAST(su.statut AS TEXT)", [
				'idutilisateur' => $idutilisateur
			]);
		} else if ($string == 'refusee') {
			$resultatRequete = self::FetchAllAssoc("SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur, su.idUtilisateur as idTuteur,idEntreprise,p.statut FROM $this->nomTable p JOIN Supervise su ON su.idOffre=p.idOffre WHERE su.idUtilisateur = :idutilisateur AND CAST(p.statut AS TEXT) != CAST(su.statut AS TEXT) AND su.statut::text = 'refusee'", [
				'idutilisateur' => $idutilisateur
			]);
			foreach ($resultatRequete as $item)
				$item['statut'] = 'refusee';
			return $resultatRequete;
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getIfStudentAlreadyAccepted(int $idOffre): bool
	{
		$resultat = self::FetchAssoc("SELECT COUNT(idUtilisateur) as nbAccepter FROM $this->nomTable WHERE idOffre=:idoffre AND (statut::text = 'validee' OR statut::text = 'en attente tuteur' OR statut::text = 'en attente responsable')", [
			'idoffre' => $idOffre
		]);
		return $resultat["nbaccepter"] == 1;
	}

	public function getIfValideeInArray(?array $candidatures): bool
	{
		foreach ($candidatures as $candidature)
			if ($candidature['statut'] == 'validee') return true;
		return false;
	}

	protected
	function getNomColonnes(): array
	{
		return [
			"sujet",
			"nom",
			"dates",
			"idOffre",
			"idUtilisateur",
			"statut"
		];
	}

	protected
	function getNomTable(): string
	{
		return "PostulerVue";
	}
}