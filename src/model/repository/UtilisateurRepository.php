<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Utilisateur;
use app\src\model\Users\User;
use mysql_xdevapi\DatabaseObject;
use PDOException;

class UtilisateurRepository extends AbstractRepository
{

    private string $nomTable = "Utilisateur";

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM $this->nomTable";
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
    public function getUserById($idUtilisateur): ?Utilisateur
    {
        try {
            $sql = "SELECT * FROM $this->nomTable WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $idUtilisateur]);
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

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Utilisateur
    {
        return new Utilisateur(
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['emailutilisateur'] ?? "",
            $dataObjectFormatTableau['nomutilisateur'] ?? "",
            $dataObjectFormatTableau['numtelutilisateur'] ?? "",
            $dataObjectFormatTableau['bio'] ?? "",
        );
    }

    protected function getNomColonnes(): array
    {
        return [
            "numtelutilisateur",
            "nomutilisateur",
            "emailutilisateur",
            "idutilisateur"
        ];
    }

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }

    /**
     * @throws ServerErrorException
     */
    public function setUserToArchived(Utilisateur $user, bool $bool): void
    {
        try {
            $sql = "UPDATE Utilisateur SET archiver = :bool WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $values = [
                'idutilisateur' => $user->getIdutilisateur(),
                'bool' => $bool ? 1 : 0
            ];
            $requete->execute($values);
            echo "L'utilisateur a été archivé";
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function isArchived(Utilisateur $utilisateur): ?bool{
        try {
            $sql = "SELECT archiver FROM Utilisateur WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $utilisateur->getIdutilisateur()]);
            $resultat = $requete->fetch();
            if ($resultat == false) {
                return null;
            }
            return $resultat['archiver'];
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }
}