<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Utilisateur;
use app\src\model\Users\User;
use mysql_xdevapi\DatabaseObject;

class UtilisateurRepository extends AbstractRepository
{

    private string $nomTable = "Utilisateur";

    public function getAll(): ?array
    {
        $sql = "SELECT * FROM $this->nomTable";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $resultat = $requete->fetchAll();
        $utilisateurs = [];
        foreach ($resultat as $utilisateur) {
            $utilisateurs[] = $this->construireDepuisTableau($utilisateur);
        }
        return $utilisateurs;
    }

    public function getUserById($idUtilisateur): ?Utilisateur
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
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
    public function setUserToArchived(Utilisateur $user): void
    {
        $sql = "UPDATE Utilisateur SET archiver = 1 WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $user->getIdutilisateur()]);
        echo "L'utilisateur a été archivé";
    }

    public function isArchived(Utilisateur $utilisateur): ?bool{
        $sql = "SELECT archiver FROM Utilisateur WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $utilisateur->getIdutilisateur()]);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $resultat['archiver'];
    }
}