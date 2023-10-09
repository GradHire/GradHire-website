<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository
{

    private string $nomTable = "Utilisateur";

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
            $dataObjectFormatTableau['numtelutilisateur'] ?? "",
            $dataObjectFormatTableau['nomutilisateur'] ?? "",
            $dataObjectFormatTableau['emailutilisateur'] ?? "",
            $dataObjectFormatTableau['idutilisateur']
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
}