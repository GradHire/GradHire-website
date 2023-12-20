<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\TuteurEntreprise;
use app\src\model\Form\FormModel;
use Exception;

class TuteurEntrepriseRepository extends ProRepository
{
	protected static string $create_function = "creerTuteur";
	protected static string $view = "tuteurvue";
	private string $nomtable = "tuteurvue";

	/**
	 * @throws ServerErrorException
	 * @throws Exception
	 */
	public static function generateAccountToken(UtilisateurRepository $entreprise, string $email, FormModel $form): void
	{
		$sql = "SELECT * FROM CreationCompteTuteur WHERE email = ? AND idUtilisateur = ?";
		$count = self::RowCount($sql, [$email, $entreprise->getId()]);
		if ($count > 0) {
			$form->setError("Cette adresse mail à déjà été ajoutée.");
			return;
		}
		$token = bin2hex(random_bytes(15));
		self::Execute("INSERT INTO CreationCompteTuteur (idUtilisateur, email, tokenCreation) VALUES (?, ?,?)", [$entreprise->getId(), $email, $token]);
		MailRepository::send_mail([$email], "Création de compte tuteur", '
<div>
<p>Vous avez été ajouté en tant que tuteur de l\'entreprise ' . $entreprise->attributes["nom"] . '</p>
<a href="' . HOST . '/registerTutor/' . $token . '">Créer mon compte</a>
</div>');
		$form->setSuccess("Un email à été envoyé à l'adresse mail indiquée.");
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function register(array $body, array $tokenData, FormModel $form): bool
	{
		$count = self::RowCount("SELECT * FROM Utilisateur WHERE email = ?", [$tokenData["email"]]);
		if ($count > 0) {
			$form->setError("Un compte existe déjà avec cette adresse mail.");
			self::deleteCreationToken($tokenData["tokencreation"]);
			return false;
		}
		$hash = self::hashPassword($body["password"]);
		$user = self::save([$body["name"], $body["surname"], $tokenData["email"], "tuteur", $tokenData["identreprise"], $hash, $body["phone"]]);
		Auth::generate_token($user, false);
		self::deleteCreationToken($tokenData["tokencreation"]);
		return true;
	}

	/**
	 * @throws ServerErrorException
	 */
	private static function deleteCreationToken(string $token): void
	{
		self::Execute("DELETE FROM CreationCompteTuteur WHERE tokenCreation = ?", [$token]);
	}


	/**
	 * @throws ServerErrorException
	 */
	public function getAllTuteursByIdEntreprise($idEntreprise, bool $getAll = false): ?array
	{
		$sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur WHERE idEntreprise = :idEntreprise" . ($getAll ? "" : " AND u.archiver = 0");
		$data = self::FetchAllAssoc($sql, ['idEntreprise' => $idEntreprise]);
		if (!$data) return null;
		$resultat = [];
		foreach ($data as $tuteur)
			$resultat[] = $this->construireTuteurProDepuisTableau($tuteur);
		return $resultat;
	}

	public function construireTuteurProDepuisTableau(array $tuteurData): ?TuteurEntreprise
	{
		return new TuteurEntreprise(
			$tuteurData
		);
	}

	public function getAll(bool $getArchived = false): ?array
	{
		$sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur WHERE u.archiver = 0";
		$data = self::FetchAllAssoc($sql);
		if (!$data) return null;
		$resultat = [];
		foreach ($data as $tuteur)
			$resultat[] = $this->construireTuteurProDepuisTableau($tuteur);
		return $resultat;
	}

	public function getNomtable(): string
	{
		return $this->nomtable;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getById($idTuteur): ?TuteurEntreprise
	{
		$sql = "SELECT * FROM $this->nomtable JOIN Utilisateur ON $this->nomtable.idUtilisateur = Utilisateur.idUtilisateur WHERE $this->nomtable.idUtilisateur = :idUtilisateur";
		$resultat = self::FetchAssoc($sql, ['idUtilisateur' => $idTuteur]);
		return $resultat ? $this->construireTuteurProDepuisTableau($resultat) : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getFullByEntreprise(mixed $idEntreprise): ?array
	{
		$sql = "SELECT * FROM $this->nomtable WHERE $this->nomtable.idEntreprise = :idEntreprise";
		$resultat = self::FetchAllAssoc($sql, ['idEntreprise' => $idEntreprise]);
		if (!$resultat) return null;
		foreach ($resultat as $key => $tuteur)
			$resultat[$key] = $this->construireTuteurProDepuisTableau($tuteur);
		return $resultat;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function create(mixed $nom, mixed $prenom, mixed $fonction, mixed $tel, mixed $email, mixed $idEntreprise)
	{
		$sql = "SELECT creerTuteur(:prenom, :nom, :email, :fonction, :idEntreprise, :hash, :tel)  ";
		return self::FetchAssoc($sql, [
			'prenom' => $prenom,
			'nom' => $nom,
			'email' => $email,
			'fonction' => $fonction,
			'idEntreprise' => $idEntreprise,
			'hash' => null,
			'tel' => $tel
		]);
	}

	protected function getNomColonnes(): array
	{
		return [
			"idUtilisateur",
			"fonction",
			"prenom",
			"idEntreprise",
		];
	}
}