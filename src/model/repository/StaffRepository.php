<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Staff;
use Exception;
use PDO;
use PDOException;

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
        try {
            $sql = "UPDATE Staff SET role = :role WHERE idutilisateur = :id";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['id' => $id, 'role' => $role]);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }


    public function role(): Roles
    {
        foreach (Roles::cases() as $case) {
            if ($this->attributes["role"] === $case->value) {
                return $case;
            }
        }
        return Roles::Teacher;
    }

    /**
     * @throws ServerErrorException
     */
    public function getAllTuteurProf(): ?array
    {
        try {
            $sql = "SELECT * FROM Supervise s JOIN StaffVue sv ON s.idUtilisateur = sv.idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $resultat = $requete->fetchAll();
            $utilisateurs = [];
            foreach ($resultat as $utilisateur) {
                $utilisateurs[] = $this->construireDepuisTableau($utilisateur);
            }
            return $utilisateurs;
        } catch (ServerErrorException $e) {
            throw new ServerErrorException($e);
        }
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
        try {
            $stmt = Database::get_conn()->prepare("SELECT email FROM StaffVue WHERE role='responsable'");
            $stmt->execute();
            $emails = [];
            foreach ($stmt->fetchAll() as $email)
                $emails[] = $email["email"];
            return $emails;
        } catch
        (Exception) {
            throw new ServerErrorException();
        }
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
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM " . self::$view;
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $resultat = $requete->fetchAll();
            $utilisateurs = [];
            foreach ($resultat as $utilisateur) {
                $utilisateurs[] = $this->construireDepuisTableau($utilisateur);
            }
            return $utilisateurs;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getCountPostulationTuteur(int $idUtilisateur): int
    {
        try {
            $stmt = Database::get_conn()->prepare("SELECT COUNT(*) as nbPosutlation FROM Supervise WHERE idUtilisateur = :idUtilisateur");
            $stmt->execute(['idUtilisateur' => $idUtilisateur]);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $stmt->fetch();
            if (!$resultat) {
                return 0;
            } else {
                return $resultat["nbposutlation"];
            }
        } catch (Exception) {
            throw new ServerErrorException('erreur getCountPostulationTuteur');
        }
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