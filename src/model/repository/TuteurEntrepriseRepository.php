<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\TuteurEntreprise;

class TuteurEntrepriseRepository extends UtilisateurRepository
{
    private string $nomtable = "TuteurEntreprise";

    public function getAllTuteursByIdEntreprise($idEntreprise): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur WHERE idEntreprise = :idEntreprise";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idEntreprise' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) return null;
        return $resultat;
    }

    public function getAll(): ?array
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur u ON u.idUtilisateur=$this->nomtable.idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) return null;
        return $resultat;
    }

    public function getNomtable(): string
    {
        return $this->nomtable;
    }

    public function getById($idTuteur): ?TuteurEntreprise
    {
        $sql = "SELECT * FROM $this->nomtable JOIN Utilisateur ON $this->nomtable.idUtilisateur = Utilisateur.idUtilisateur WHERE $this->nomtable.idUtilisateur = :idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $idTuteur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) {
            return null;
        }
        return $this->construireTuteurProDepuisTableau($resultat);
    }

    public function construireTuteurProDepuisTableau(array $tuteurData): ?TuteurEntreprise
    {
        return new TuteurEntreprise(
            $tuteurData['idutilisateur'],
            $tuteurData['prenom'] ?? "",
            $tuteurData['fonction'] ?? "",
            $tuteurData['identreprise'],
            $tuteurData['email'] ?? "",
            $tuteurData['nom'] ?? "",
            $tuteurData['numtelephone'] ?? "",

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