<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Staff;

class StaffRepository extends LdapRepository
{
	protected static string $view = "StaffVue";
	protected static string $create_function = "creerStaff";
	protected static string $update_function = "updateStaff";

	/**
	 * @throws ServerErrorException
	 */
	public static function updateRole($id, $role): void
	{
		self::Execute("UPDATE Staff SET role = :role WHERE idUtilisateur = :id", [":id" => $id, ":role" => $role]);
	}

	public function role(): Roles
	{
		foreach (Roles::cases() as $case)
			if ($this->attributes["role"] === $case->value)
				return $case;
		return Roles::Teacher;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getAllTuteurProf(): ?array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM Supervise s JOIN StaffVue sv ON s.idUtilisateur = sv.idUtilisateur");
		$utilisateurs = [];
		foreach ($resultat as $utilisateur)
			$utilisateurs[] = $this->construireDepuisTableau($utilisateur);
		return $utilisateurs;
	}

	protected
	function construireDepuisTableau(array $dataObjectFormatTableau): Staff
	{
		return new Staff(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getManagersEmail(): array
	{
		$data = self::FetchAll("SELECT email FROM StaffVue WHERE role='responsable'");
		$emails = [];
		foreach ($data as $email)
			$emails[] = $email["email"];
		return $emails;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByNomPreFull(mixed $nom, mixed $prenom): ?Staff
	{
		$resultat = self::FetchAssoc("SELECT * FROM " . self::$view . " WHERE nom = :nom AND prenom=:prenom", ['nom' => $nom, 'prenom' => $prenom]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getByIdFull($idutilisateur): ?Staff
	{
		$resultat = self::FetchAssoc("SELECT * FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur", ['idUtilisateur' => $idutilisateur]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	public function getAll(): ?array
	{
		$resultat = self::FetchAll("SELECT * FROM " . self::$view);
		$utilisateurs = [];
		foreach ($resultat as $utilisateur)
			$utilisateurs[] = $this->construireDepuisTableau($utilisateur);
		return $utilisateurs;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getCountPostulationTuteur(int $idUtilisateur): int
	{
		$resultat = self::FetchAssoc("SELECT COUNT(*) as nbPosutlation FROM Supervise WHERE idUtilisateur = :idUtilisateur", ['idUtilisateur' => $idUtilisateur]);
		return $resultat ? $resultat["nbposutlation"] : 0;
	}

	protected
	function getNomColonnes(): array
	{
		return ["idutilisateur", "role", "loginLdap", "prenom", "archiver", "idtuteurentreprise"];
	}

	protected
	function getNomTable(): string
	{
		return "StaffVue";
	}
}