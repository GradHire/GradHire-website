<?php
namespace app\src\model;
use app\src\model\Offre;
use app\src\core\db\Database;

class OffreForm{
    public static function creerOffre(Offre $offre){
        $sql = "INSERT INTO Offre VALUES (null ,:dureeTag, :thematiqueTag, :sujetTag, :nbJourTravailHebdoTag, :nbHeureTravailHebdoTag, :gratificationTag, :unitegratificationTag, :avantageNatureTag, :dateDebutTag, :dateFinTag, :statutTag, :anneeViseeTag, :idAnneeTag, :idUtilisateurTag, :descriptionTag)";
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "dureeTag" => $offre->getDuree(),
            "thematiqueTag" => $offre->getThematique(),
            "sujetTag" => $offre->getSujet(),
            "nbJourTravailHebdoTag" => $offre->getNbJourTravailHebdo(),
            "nbHeureTravailHebdoTag" => $offre->getNbHeureTravailHebdo(),
            "gratificationTag" => $offre->getGratification(),
            "unitegratificationTag" => $offre->getUnitegratification(),
            "avantageNatureTag" => $offre->getAvantageNature(),
            "dateDebutTag" => $offre->getDateDebut(),
            "dateFinTag" => $offre->getDateFin(),
            "statutTag" => $offre->getStatut(),
            "anneeViseeTag" => $offre->getAnneeVisee(),
            "idAnneeTag" => $offre->getIdAnnee(),
            "idUtilisateurTag" => $offre->getIdUtilisateur(),
            "descriptionTag" => $offre->getDescription(),
        );
        try {
            $pdoStatement->execute($values);
            $id= Database::get_conn()->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
        if($offre->getAlternance()!=null){
            $sql = "INSERT INTO offreAlternance VALUES (:idOffreTag, :alternanceTag)";
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = array(
                "idOffreTag" => $id,
                "alternanceTag" => $offre->getAlternance(),
            );
            try {
                $pdoStatement->execute($values);
            } catch (PDOException $e) {
                return false;
            }
        }
        else{
            $sql = "INSERT INTO offreStage VALUES (:idOffreTag)";
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = array(
                "idOffreTag" => $id,
            );
            try {
                $pdoStatement->execute($values);
            } catch (PDOException $e) {
                return false;
            }
        }
        return true;
    }
}