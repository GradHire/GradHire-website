<?php
namespace app\src\model;
use app\src\core\db\Database;
use app\src\model\dataObject\Offre;

class OffreForm extends Model{
    public static function creerOffre(Offre $offre, ?float $distanciel){
        $sql = "INSERT INTO Offre VALUES (null ,:dureeTag, :thematiqueTag, :sujetTag, :nbJourTravailHebdoTag, :nbHeureTravailHebdoTag, :gratificationTag, :unitegratificationTag, :avantageNatureTag, :dateDebutTag, :dateFinTag, :anneeViseeTag, :idAnneeTag, :idUtilisateurTag, :descriptionTag,:dateCreationTag,:statutTag)";
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "dureeTag" => $offre->getDuree(),
            "thematiqueTag" => $offre->getThematique(),
            "sujetTag" => $offre->getSujet(),
            "nbJourTravailHebdoTag" => $offre->getNbjourtravailhebdo(),
            "nbHeureTravailHebdoTag" => $offre->getNbHeureTravailHebdo(),
            "gratificationTag" => $offre->getGratification(),
            "unitegratificationTag" => $offre->getUnitegratification(),
            "avantageNatureTag" => $offre->getAvantageNature(),
            "dateDebutTag" => $offre->getDateDebut(),
            "dateFinTag" => $offre->getDateFin(),
            "anneeViseeTag" => $offre->getAnneeVisee(),
            "idAnneeTag" => $offre->getIdAnnee(),
            "idUtilisateurTag" => $offre->getIdutilisateur(),
            "descriptionTag" => $offre->getDescription(),
            "statutTag" => $offre->getStatut(),
            "dateCreationTag" => $offre->getDateCreation(),
        );
        try {
            $pdoStatement->execute($values);
            $id= Database::get_conn()->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
        if($distanciel!=null){
            $sql = "INSERT INTO Offrealternance VALUES (:idOffreTag, :alternanceTag)";
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = array(
                "idOffreTag" => $id,
                "alternanceTag" => $distanciel,
            );
            try {
                $pdoStatement->execute($values);
            } catch (PDOException $e) {
                return false;
            }
        }
        else{
            $sql = "INSERT INTO Offrestage VALUES (:idOffreTag)";
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
    public static function updateOffre(Offre $offre, ?float $distanciel){
        $sql = "UPDATE Offre SET duree=:dureeTag, thematique=:thematiqueTag, sujet=:sujetTag, nbjourtravailhebdo=:nbJourTravailHebdoTag, nbHeureTravailHebdo=:nbHeureTravailHebdoTag, gratification=:gratificationTag, unitegratification=:unitegratificationTag, avantageNature=:avantageNatureTag, dateDebut=:dateDebutTag, dateFin=:dateFinTag, anneeVisee=:anneeViseeTag, idAnnee=:idAnneeTag, idUtilisateur=:idUtilisateurTag, description=:descriptionTag, status=:statutTag WHERE idOffre=:idOffreTag";
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "idOffreTag" => $offre->getIdOffre(),
            "dureeTag" => $offre->getDuree(),
            "thematiqueTag" => $offre->getThematique(),
            "sujetTag" => $offre->getSujet(),
            "nbJourTravailHebdoTag" => $offre->getNbjourtravailhebdo(),
            "nbHeureTravailHebdoTag" => $offre->getNbHeureTravailHebdo(),
            "gratificationTag" => $offre->getGratification(),
            "unitegratificationTag" => $offre->getUnitegratification(),
            "avantageNatureTag" => $offre->getAvantageNature(),
            "dateDebutTag" => $offre->getDateDebut(),
            "dateFinTag" => $offre->getDateFin(),
            "anneeViseeTag" => $offre->getAnneeVisee(),
            "idAnneeTag" => $offre->getIdAnnee(),
            "idUtilisateurTag" => $offre->getIdutilisateur(),
            "descriptionTag" => $offre->getDescription(),
            "statutTag" => $offre->getStatut(),
        );
        try {
            $pdoStatement->execute($values);
        } catch (PDOException $e) {
            return false;
        }
        if($distanciel!=null){
            $sql = "UPDATE Offrealternance SET alternance=:alternanceTag WHERE idOffre=:idOffreTag";
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = array(
                "idOffreTag" => $offre->getIdOffre(),
                "alternanceTag" => $distanciel,
            );
            try {
                $pdoStatement->execute($values);
            } catch (PDOException $e) {
                return false;
            }
        }

    }

    public static function deleteOffre(mixed $idOffre)
    {
        $sql = "DELETE FROM Offre WHERE idOffre=:idOffreTag";
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "idOffreTag" => $idOffre,
        );
        try {
            $pdoStatement->execute($values);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
}