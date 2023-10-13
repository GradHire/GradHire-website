<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Staff;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Utilisateur;
use PDOException;

class StaffRepository extends UtilisateurRepository
{

    private static string $view = "StaffVue";

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Staff
    {
        return new Staff(
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["emailutilisateur"],
            $dataObjectFormatTableau["nomutilisateur"],
            $dataObjectFormatTableau["numtelutilisateur"],
            $dataObjectFormatTableau["prenomutilisateurldap"],
            $dataObjectFormatTableau["loginldap"],
            $dataObjectFormatTableau["role"],
            $dataObjectFormatTableau["mailuni"]
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Staff
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $idutilisateur]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat == false) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getManagersEmail(): array
    {
        try {
            $stmt = Database::get_conn()->prepare("SELECT emailutilisateur FROM StaffVue WHERE role='responsable'");
            $stmt->execute();
            $emails = [];
            foreach ($stmt->fetchAll() as $email)
                $emails[] = $email["emailutilisateur"];
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
            "emailutilisateur",
            "nomutilisateur",
            "numtelutilisateur",
            "prenomutilisateurldap",
            "loginldap",
            "role",
            "mailuni"
        ];
    }

    protected function getNomTable(): string
    {
        return "StaffVue";
    }
}