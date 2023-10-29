<?php

namespace app\src\model\repository;

use app\src\model\dataObject\TuteurPro;
use app\src\core\db\Database;

class TuteurProRepository extends UtilisateurRepository
{
    private string $nomtable = "Tuteurprofessionnel";

    public function getAllTuteursByIdEntreprise($idEntreprise): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur WHERE idEntreprise = :idEntreprise";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idEntreprise' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) return null;
        return $resultat;
    }

    public function getAll(): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) return null;
        return $resultat;
    }

    public function getNomtable(): string
    {
        return $this->nomtable;
    }

    public function getById($idTuteur): ?TuteurPro
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur ON $this->nomtable.idUtilisateur = Utilisateur.idUtilisateur WHERE $this->nomtable.idUtilisateur = :idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $idTuteur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireTuteurProDepuisTableau($resultat);
    }

    public function construireTuteurProDepuisTableau(array $tuteurData): ?TuteurPro
    {
        return new TuteurPro(
            $tuteurData['idUtilisateur'],
            $tuteurData['prenom'] ?? "",
            $tuteurData['fonction'] ?? "",
            $tuteurData['idEntreprise'],
            $tuteurData['emailUtilisateur'] ?? "",
            $tuteurData['nomUtilisateur'] ?? "",
            $tuteurData['numTelUtilisateur'] ?? "",

        );
    }

    protected function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "fonction",
            "prenom",
            "idEntreprise",
        ];
    }


}