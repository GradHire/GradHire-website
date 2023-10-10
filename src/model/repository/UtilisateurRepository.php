<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Utilisateur;
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
            $dataObjectFormatTableau['numtelutilisateur'] ?? ""
        );
    }

    public function getUserNom($idUtilisateur): ?string
    {
        $sql = "SELECT nomutilisateur FROM $this->nomTable WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $resultat['nomutilisateur'];
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
}