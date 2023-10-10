<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Candidature;

class CandidatureRepository extends AbstractRepository
{

    protected function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject
    {
        return new Candidature(
            $dataObjectFormatTableau['idcandidature'],
            $dataObjectFormatTableau['datecandidature'],
            $dataObjectFormatTableau['etatcandidature'],
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['idutilisateur']
        );
    }
    protected function getNomColonnes(): array
    {
        return [
            "idcandidature",
            "datecandidature",
            "etatcandidature",
            "idoffre",
            "idutilisateur"
        ];
    }
    public function getAll(): ?array
    {
        $sql= "SELECT * FROM Candidature ";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) return null;
        return $resultat;
    }

    protected function getNomTable(): string
    {
        return "Candidature";
    }
}