<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Etudiant;
use app\src\model\dataObject\Roles;

class EtudiantRepository extends LdapRepository
{
	protected static string $view = "EtudiantVue";
	protected static string $create_function = "creerEtu";
	protected static string $update_function = "updateEtudiant";

	/**
	 * @throws ServerErrorException
	 */
	public static function getNewsletterEmails($idOffre): array
	{
		$offre = OffresRepository::getInfosForNewsletter($idOffre);
		if (!$offre) return [];
		$estStage = !is_null($offre["est_stage"]);
		$estAlternance = !is_null($offre["est_alternance"]);
		$type = 'all';
		if ($estStage != $estAlternance)
			$type = $estStage ? "stage" : "alternance";
		$annee = $offre["anneevisee"] > 0 ? strval($offre["anneevisee"]) : "all";

		$sql = "SELECT e.email FROM Newsletter n
    JOIN EtudiantVue e ON n.idUtilisateur = e.idUtilisateur
    WHERE (n.annee = 'all' OR n.annee=?)
    AND (n.thematiques = '' OR n.thematiques LIKE ?)
    AND (n.offre_type = 'all' OR n.offre_type=?)";

		$resultat = self::FetchAllAssoc($sql, [$annee, "%" . $offre["thematique"] . "%", $type]);
		if (!$resultat) return [];
		$emails = [];
		foreach ($resultat as $email)
			$emails[] = $email["email"];
		return $emails;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getEtuSansConv(): ?array
	{
		$sql = "SELECT idUtilisateur FROM EtudiantVue WHERE idUtilisateur NOT IN (SELECT idUtilisateur FROM Convention)";
		$resultat = self::FetchAllAssoc($sql);
		if (!$resultat) return null;
		$etudiant = new EtudiantRepository([]);
		$etudiants = [];
		foreach ($resultat as $etu)
			$etudiants[] = $etudiant->getByIdFull($etu["idutilisateur"]);
		return $etudiants;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getByIdFull($idutilisateur): ?Etudiant
	{
		$sql = "SELECT * FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur";
		$resultat = self::FetchAssoc($sql, ['idUtilisateur' => $idutilisateur]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	protected
	function construireDepuisTableau(array $dataObjectFormatTableau): Etudiant
	{
		return new Etudiant(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getFullNameByID(int $id): string
	{
		$sql = "SELECT nom, prenom FROM etudiantvue where idutilisateur=?";
		$data = self::FetchAssoc($sql, [$id]);
		return $data ? $data["prenom"] . " " . $data["nom"] : "";
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function subscribeNewsletter($parameters): void
	{
		$sql = "
            INSERT INTO Newsletter (idUtilisateur, offre_type, annee, thematiques)
            VALUES (?, ?, ?, ?)
            ON CONFLICT (idUtilisateur) DO UPDATE
            SET offre_type = excluded.offre_type,
                annee = excluded.annee,
                thematiques = excluded.thematiques";
		self::Execute($sql, [
			Application::getUser()->id,
			$parameters["type"],
			$parameters["year"],
			implode(",", $parameters["theme"])
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getEmailById(mixed $idutilisateur): ?array
	{
		$sql = "SELECT email FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur";
		return self::Fetch($sql, ['idUtilisateur' => $idutilisateur]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getAllEleveNoConvention(): ?array
	{
		$sql = "SELECT email, idutilisateur, nom, prenom FROM " . self::$view . " WHERE idUtilisateur NOT IN (SELECT idUtilisateur FROM Convention)";
		return self::FetchAll($sql);
	}

	public function role(): Roles
	{
		return Roles::Student;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function update_year(string $new_year): void
	{
		self::Execute("UPDATE etudiantvue SET annee=? WHERE idUtilisateur=?", [$new_year, $this->id]);
	}


	/**
	 * @throws ServerErrorException
	 */
	public function getByNumEtudiantFull($numEtudiant): ?Etudiant
	{
		$sql = "SELECT * FROM " . self::$view . " WHERE numEtudiant = :numEtudiant";
		$data = self::FetchAssoc($sql, ['numEtudiant' => $numEtudiant]);
		return $data ? $this->construireDepuisTableau($data) : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function updateEtu(string $numEtu, string $nom, string $prenom, string $tel, string $mailPerso, string $mailUniv, string $adresse, string $codePostal, string $ville, string $pays, ?string $groupe): void
	{
		self::Execute("Call updateetuimp(?,?,?,?,?,?,?,?,?,?,?,?)", [$numEtu, $nom, $prenom, $tel, $mailPerso, $mailUniv, null, $adresse, $codePostal, $pays, $ville, $groupe]);
	}

	protected
	function getNomColonnes(): array
	{
		return [
			"idUtilisateur",
			"email",
			"nom",
			"numTelephone",
			"bio",
			"archiver",
			"nomVille",
			"codePostal",
			"pays",
			"adresse",
			"emailPerso",
			"numEtudiant",
			"codeSexe",
			"idGroupe",
			"annee",
			"dateNaissance",
			"loginLdap",
			"prenom"
		];
	}

	protected
	function getNomTable(): string
	{
		return "EtudiantVue";
	}
}
