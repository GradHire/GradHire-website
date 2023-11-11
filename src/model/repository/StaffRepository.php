<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Staff;
use PDOException;

class StaffRepository extends LdapRepository
{
    protected static string $view = "StaffVue";
    protected static string $create_function = "creerStaff";
    protected static string $update_function = "updateStaff";

    public function role(): Roles
    {
        if ($this->attributes["role"] === "responsable")
            return Roles::Manager;
        return Roles::Staff;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getManagersEmail(): array
    {
        try {
            $stmt = Database::get_conn()->prepare("SELECT email FROM StaffVue WHERE role='responsable'");
            $stmt->execute();
            $emails = [];
            foreach ($stmt->fetchAll() as $email)
                $emails[] = $email["email"];
            return $emails;
        } catch
        (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function getByNomPreFull(mixed $nom, mixed $prenom)
    {
        $sql = "SELECT * FROM " . self::$view . " WHERE nom = :nom AND prenom=:prenom";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['nom' => $nom, 'prenom' => $prenom]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();

        if (!$resultat) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Staff
    {
        return new Staff(
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["role"],
            $dataObjectFormatTableau["loginldap"],
            $dataObjectFormatTableau["prenom"],
            $dataObjectFormatTableau["email"],
            $dataObjectFormatTableau["nom"],
            $dataObjectFormatTableau["numtelephone"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["archiver"]
        );
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

    protected
    function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "role",
            "email",
            "loginldap",
            "prenom",
            "email",
            "nom",
            "numtelephone",
            "mailuni",
            "bio",
            "archiver"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "StaffVue";
    }
}