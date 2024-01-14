<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Staff;

class StaffRepository extends LdapRepository
{
    protected static string $view = "StaffVue";
    protected static string $create_function = "creerStaff";
    protected static string $update_function = "updatestaff";

    /**
     * @throws ServerErrorException
     */
    public static function updateRole($id, $role): void
    {
        self::Execute("UPDATE Staff SET role = :role WHERE idutilisateur = :id", ['id' => $id, 'role' => $role]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getCountPostulationTuteur(int $idUtilisateur): int
    {
        $data = self::FetchAssoc("SELECT COUNT(*) as nbPosutlation FROM Supervise WHERE idUtilisateur = :idUtilisateur", ['idUtilisateur' => $idUtilisateur]);
        return $data ? $data["nbposutlation"] : 0;
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
        $resultat = self::FetchAll("SELECT * FROM Supervise s JOIN StaffVue sv ON s.idUtilisateur = sv.idUtilisateur") ?? [];
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
        $data = self::FetchAll("SELECT email FROM StaffVue WHERE role='responsable'") ?? [];
        $emails = [];
        foreach ($data as $email)
            $emails[] = $email["email"];
        return $emails;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Staff
    {
        $sql = "SELECT * FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur";
        $resultat = self::Fetch($sql, ['idUtilisateur' => $idutilisateur]);
        return $resultat ? $this->construireDepuisTableau($resultat) : null;
    }

    public function getAll(): ?array
    {
        $resultat = self::FetchAll("SELECT * FROM " . self::$view) ?? [];
        $utilisateurs = [];
        foreach ($resultat as $utilisateur)
            $utilisateurs[] = $this->construireDepuisTableau($utilisateur);
        return $utilisateurs;
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