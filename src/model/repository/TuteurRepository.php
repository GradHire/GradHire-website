<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Staff;
use app\src\model\dataObject\Tuteur;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Utilisateur;

class TuteurRepository extends UtilisateurRepository
{

    private static string $view = "TuteurVue";

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Tuteur
    {
        return new Tuteur(
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["emailutilisateur"],
            $dataObjectFormatTableau["nomutilisateur"],
            $dataObjectFormatTableau["numtelutilisateur"],
            $dataObjectFormatTableau["hash"],
            $dataObjectFormatTableau["prenomtuteurp"],
            $dataObjectFormatTableau["fonctiontuteurp"],
            $dataObjectFormatTableau["identreprise"]
        );
    }

    public function getByIdFull($idutilisateur): ?Tuteur
    {
        $sql = "SELECT * FROM " . self::$view . " WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idutilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
    }

    protected function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "bio",
            "emailutilisateur",
            "nomutilisateur",
            "numtelutilisateur",
            "hash",
            "prenomtuteurp",
            "fonctiontuteurp",
            "identreprise"
        ];
    }

    protected function getNomTable(): string
    {
        return "TuteurVue";
    }

}