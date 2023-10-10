<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Offre;

class OffresRepository extends AbstractRepository
{
    private string $nomTable = "Offre";

    public function recupererParId($idOffre): ?Offre
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idoffre = :idoffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idoffre' => $idOffre]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Offre
    {
        return new Offre(
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['duree'],
            $dataObjectFormatTableau['thematique'],
            $dataObjectFormatTableau['sujet'],
            $dataObjectFormatTableau['nbjourtravailhebdo'],
            $dataObjectFormatTableau['nbheuretravailhebdo'],
            $dataObjectFormatTableau['gratification'],
            $dataObjectFormatTableau['unitegratification'],
            $dataObjectFormatTableau['avantagenature'],
            $dataObjectFormatTableau['datedebut'],
            $dataObjectFormatTableau['datefin'],
            $dataObjectFormatTableau['status'],
            $dataObjectFormatTableau['anneevisee'],
            $dataObjectFormatTableau['idannee'],
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['description'],
//            $dataObjectFormatTableau['alternance']
        );
    }

    protected function getNomColonnes(): array
    {
        return [
            "duree",
            "thematique",
            "sujet",
            "nbjourtravailhebdo",
            "nbheureTravailhebdo",
            "gratification",
            "unitegratification",
            "avantagenature",
            "datedebut",
            "datefin",
            "statut",
            "anneevisee"
        ];
    }

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }

}
