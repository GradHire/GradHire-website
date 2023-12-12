<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;

class EntrepriseRepository extends ProRepository
{
	protected static string $view = "entreprisevue";
	protected static string $create_function = "creerEntreprise";
	protected static string $update_function = "updateEntreprise";

	/**
	 * @throws ServerErrorException
	 */
	public static function register(array $body, FormModel $form): bool
	{
		try {
			$exist = self::exist($body["email"], $body["siret"]);
			if ($exist) {
				$form->setError("Un compte existe déjà avec cette adresse mail ou ce numéro de siret.");
				return false;
			}
			$user = self::save([$body["name"], $body["siret"], $body["email"], password_hash($body["password"], PASSWORD_DEFAULT), $body["phone"]]);
			$emails = (new StaffRepository([]))->getManagersEmail();
			MailRepository::send_mail($emails, "Nouvelle entreprise",
				'<div>
 <p>L\'entreprise ' . $body["name"] . ' viens de créer un compte</p>
 <a href="' . HOST . '/entreprises/' . $user->id() . '">Voir le profile</a>
 </div>');
			Auth::generate_token($user, false);
			return true;
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function exist(string $email, string $siret): bool
	{
		return self::CheckExist("SELECT * FROM " . static::$view . " WHERE email=? OR siret=?", [$email, $siret]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getTuteurWaitingList(string $id): array
	{
		$resultat = self::FetchAllAssoc("SELECT email FROM CreationCompteTuteur WHERE idUtilisateur = ?", [$id]);
		return !$resultat ? [] : $resultat;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getList(): array
	{
		$resultat = self::FetchAll("SELECT idUtilisateur, nom FROM EntrepriseVue");
		return !$resultat ? [] : $resultat;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getEmailById($id): ?array
	{
		return self::Fetch("SELECT email FROM " . static::$view . " WHERE idUtilisateur = ?", [$id]);
	}

	public
	function role(): Roles
	{
		return Roles::Enterprise;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getByIdFull($idEntreprise): ?Entreprise
	{
		$resultat = self::FetchAssoc("SELECT * FROM " . $this->getNomTable() . " WHERE " . $this->getNomTable() . ".idUtilisateur = ?", [$idEntreprise]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	protected
	function getNomTable(): string
	{
		return self::$view;
	}

	protected
	function construireDepuisTableau(array $dataObjectFormatTableau): Entreprise
	{
		return new Entreprise(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getAll(): ?array
	{
		$resultat = self::FetchAll("SELECT * FROM " . $this->getNomTable() . " e JOIN Utilisateur u ON e.idUtilisateur = u.idUtilisateur");
		if (!$resultat) return null;
		$entreprises = [];
		foreach ($resultat as $entrepriseData)
			$entreprises[] = $this->construireDepuisTableau($entrepriseData);

		return $entreprises;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByName(mixed $nomEnt, mixed $pays, mixed $department): ?array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM " . $this->getNomTable() . " WHERE nom = ? AND pays = ? AND codePostal LIKE ?", [$nomEnt, $pays, $department . '%']);
		if (!$resultat) return null;
		$entreprises = [];
		foreach ($resultat as $entrepriseData)
			$entreprises[] = $this->construireDepuisTableau($entrepriseData);
		return $entreprises;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getBySiret(mixed $siret, mixed $siren): ?array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM " . $this->getNomTable() . " WHERE siret = ? AND siret LIKE  ?", [$siret, $siren . '%']);
		if (!$resultat) return null;
		$entreprises = [];
		foreach ($resultat as $entrepriseData)
			$entreprises[] = $this->construireDepuisTableau($entrepriseData);
		return $entreprises;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByTel(mixed $tel, mixed $fax): ?array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM " . $this->getNomTable() . " WHERE numTelephone = ? AND fax = ?", [$tel, $fax]);
		if (!$resultat) return null;
		$entreprises = [];
		foreach ($resultat as $entrepriseData)
			$entreprises[] = $this->construireDepuisTableau($entrepriseData);
		return $entreprises;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByAdresse(mixed $adresse, mixed $codePostal, mixed $pays): ?array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM " . $this->getNomTable() . " WHERE adresse = ? AND codePostal = ? AND pays = ?", [$adresse, $codePostal, $pays]);
		if (!$resultat) return null;
		$entreprises = [];
		foreach ($resultat as $entrepriseData)
			$entreprises[] = $this->construireDepuisTableau($entrepriseData);
		return $entreprises;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getCodePostal(int $idUtilisateur): ?string
	{
		$resultat = self::FetchAssoc("SELECT codePostal FROM " . $this->getNomTable() . " WHERE idUtilisateur = ?", [$idUtilisateur]);
		if (!$resultat) return null;
		return $resultat['codepostal'];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getVille(int $idUtilisateur): ?string
	{
		$resultat = self::FetchAssoc("SELECT nomVille FROM " . $this->getNomTable() . " WHERE idUtilisateur = ?", [$idUtilisateur]);
		if (!$resultat) return null;
		return $resultat['nomville'];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getPays(int $idUtilisateur): ?string
	{
		$resultat = self::FetchAssoc("SELECT pays FROM " . $this->getNomTable() . " WHERE idUtilisateur = ?", [$idUtilisateur]);
		if (!$resultat) return null;
		return $resultat['pays'];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function create(mixed $nomEnt, mixed $email, mixed $tel, string $string, mixed $type, mixed $effectif, mixed $codeNaf, mixed $fax, mixed $web, mixed $voie, mixed $cedex, mixed $residence, mixed $codePostal, mixed $pays, mixed $nomville, mixed $siret)
	{
		$sql = "SELECT creerentimp(:nomEnt, :email, :tel, :string, :type, :effectif, :codeNaf, :fax, :web, :voie, :cedex, :residence, :codePostal, :pays, :nomville, :siret) ";
		self::Execute($sql, ['nomEnt' => $nomEnt, 'email' => $email, 'tel' => $tel, 'string' => $string, 'type' => $type, 'effectif' => $effectif, 'codeNaf' => $codeNaf, 'fax' => $fax, 'web' => $web, 'voie' => $voie, 'cedex' => $cedex, 'residence' => $residence, 'codePostal' => $codePostal, 'pays' => $pays, 'nomville' => $nomville, 'siret' => $siret]);
	}


	protected
	function getNomColonnes(): array
	{
		return [
			"idUtilisateur",
			"statutJuridique",
			"bio",
			"typeStructure",
			"effectif",
			"codeNaf",
			"fax",
			"siteWeb",
			"siret",
			"email",
			"nom",
			"numTelephone",
			"adresse",
			"codePostal",
			"nomville",
			"pays"
		];
	}
}
