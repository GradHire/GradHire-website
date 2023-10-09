<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Utilisateur;

class EntrepriseRepository extends UtilisateurRepository
{
    private string $nomTable = "Entreprise";

    public function getById($idEntreprise): ?Entreprise
    {
        $user = $this->getUserById($idEntreprise);
        $sql = "SELECT * FROM $this->nomTable WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireEntrepriseDepuisTableau($user, $resultat);
    }

    protected function construireEntrepriseDepuisTableau(Utilisateur $utilisateur, array $entrepriseData): Entreprise
    {
        return new Entreprise(
            $utilisateur,
            $entrepriseData['statutjuridique'] ?? "",
            $entrepriseData['typestructure'] ?? "",
            $entrepriseData['effectif'] ?? "",
            $entrepriseData['codenaf'] ?? "",
            $entrepriseData['fax'] ?? "",
            $entrepriseData['siteweb'] ?? "",
            $entrepriseData['siret'] ?? 0,
            $entrepriseData['validee'] ?? 0
        );
    }

    public function getOffresByEntreprise($idEntreprise): ?array
    {
        $sql = "SELECT * FROM Offre WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) {
            return null;
        }
        return $resultat;
    }

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }

    protected function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "statutjuridique",
            "typestructure",
            "effectif",
            "codenaf",
            "fax",
            "siteweb",
            "siret",
            "validee"
        ];
    }
}