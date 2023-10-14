<?php

namespace app\src\model\repository;
use app\src\model\dataObject\TuteurPro;
use app\src\core\db\Database;
class TuteurProRepository extends UtilisateurRepository
{
    private string $nomtable="Tuteurprofessionnel";

    public function getAllTuteursByIdEntreprise($idEntreprise): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idutilisateur=$this->nomtable.idutilisateur WHERE identreprise = :identreprise";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['identreprise' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) return null;
        return $resultat;
    }

    protected function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "fonctiontuteurp",
            "prenomtuteurp",
            "identreprise",
        ];
    }
    public function construireTuteurProDepuisTableau(array $tuteurData): ?TuteurPro
    {
        return new TuteurPro(
            $tuteurData['idutilisateur'],
            $tuteurData['prenomtuteurp'] ?? "",
            $tuteurData['fonctiontuteurp'] ?? "",
            $tuteurData['identreprise'],
            $tuteurData['emailutilisateur'] ?? "",
            $tuteurData['nomutilisateur'] ?? "",
            $tuteurData['numtelutilisateur'] ?? "",
            
        );
    }

    public function getNomtable(): string
    {
        return $this->nomtable;
    }
    public function getById($idTuteur): ?TuteurPro
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur ON $this->nomtable.idutilisateur = Utilisateur.idutilisateur WHERE $this->nomtable.idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idTuteur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireTuteurProDepuisTableau($resultat);
    }



}