<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Staff;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Utilisateur;
use app\src\model\dataObject\Roles;
use PDOException;
use app\src\model\repository\LdapRepository;

class StaffRepository extends LdapRepository
{
    protected static string $view = "StaffVue";
    protected static string $create_function = "creerStaff";
    protected static string $update_function = "updateStaff";

    public function role(): Roles
    {
        return Roles::tryFrom($this->attributes["role"]);
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Staff
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $idutilisateur]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Staff
    {
        return new Staff(
            $dataObjectFormatTableau["idUtilisateur"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["emailUtilisateur"],
            $dataObjectFormatTableau["nomUtilisateur"],
            $dataObjectFormatTableau["numTelUtilisateur"],
            $dataObjectFormatTableau["prenomLdap"],
            $dataObjectFormatTableau["loginLdap"],
            $dataObjectFormatTableau["role"],
            $dataObjectFormatTableau["mailUni"]
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getManagersEmail(): array
    {
        try {
            $stmt = Database::get_conn()->prepare("SELECT emailUtilisateur FROM StaffVue WHERE role='responsable'");
            $stmt->execute();
            $emails = [];
            foreach ($stmt->fetchAll() as $email)
                $emails[] = $email["emailUtilisateur"];
            return $emails;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    protected function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "bio",
            "emailUtilisateur",
            "nomUtilisateur",
            "numTelUtilisateur",
            "prenomLdap",
            "loginLdap",
            "role",
            "mailUni"
        ];
    }

    protected function getNomTable(): string
    {
        return "StaffVue";
    }
}