<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Entreprise;

class EntrepriseRepository extends UtilisateurRepository
{
    private string $nomTable = "EntrepriseVue";

    public function getByIdFull($idEntreprise): ?Entreprise
    {
        $sql = "SELECT * FROM $this->nomTable WHERE $this->nomTable.idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idEntreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
    }

    protected function construireDepuisTableau(array $entrepriseData): Entreprise
    {
        return new Entreprise(
            $entrepriseData['idutilisateur'],
            $entrepriseData['bio'] ?? "",
            $entrepriseData['statutjuridique'] ?? "",
            $entrepriseData['typestructure'] ?? "",
            $entrepriseData['effectif'] ?? "",
            $entrepriseData['codenaf'] ?? "",
            $entrepriseData['fax'] ?? "",
            $entrepriseData['siteweb'] ?? "",
            $entrepriseData['siret'] ?? 0,
            $entrepriseData['validee'] ?? 0,
            $entrepriseData['emailutilisateur'] ?? "",
            $entrepriseData['nomutilisateur'] ?? "",
            $entrepriseData['numtelutilisateur'] ?? ""
        );
    }

    public function getAll(): ?array
    {
        $sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idutilisateur = Utilisateur.idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) {
            return null;
        }
        $entreprises = [];
        foreach ($resultat as $entrepriseData) {
            $entreprises[] = $this->construireEntrepriseDepuisTableau($entrepriseData);
        }
        return $entreprises;
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