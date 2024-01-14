<?php

namespace app\src\model;

use app\src\core\db\Database;
use app\src\model\dataObject\Offre;
use app\src\model\repository\MailRepository;
use app\src\model\repository\StaffRepository;
use PDOException;

class OffreForm extends Model
{
    public static function creerOffre(Offre $offre, ?string $typeStage, ?string $typeAlternance, ?float $distanciel)
    {
        $sql = "SELECT idoffre FROM Offre WHERE idoffre = (SELECT MAX(idoffre) FROM Offre)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetch();
        $id = $id["idoffre"];
        $sql = "INSERT INTO Offre VALUES (:id,:dureeTag, :thematiqueTag, :sujetTag, :nbJourTravailHebdoTag, :nbHeureTravailHebdoTag, :gratificationTag, :avantageNatureTag, :dateDebutTag, :dateFinTag, :statutTag, :pourvueTag, :anneeViseeTag, :idAnneeTag, :idUtilisateurTag, :dateCreationTag, :descriptionTag)";
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "id" => $id + 1,
            "dureeTag" => $offre->getDuree(),
            "thematiqueTag" => $offre->getThematique(),
            "sujetTag" => $offre->getSujet(),
            "nbJourTravailHebdoTag" => $offre->getNbjourtravailhebdo(),
            "nbHeureTravailHebdoTag" => $offre->getNbHeureTravailHebdo(),
            "gratificationTag" => $offre->getGratification(),
            "avantageNatureTag" => $offre->getAvantageNature(),
            "dateDebutTag" => $offre->getDateDebut(),
            "dateFinTag" => $offre->getDateFin(),
            "statutTag" => $offre->getStatut(),
            "pourvueTag" => $offre->getPourvue(),
            "anneeViseeTag" => $offre->getAnneeVisee(),
            "idAnneeTag" => $offre->getAnnee(),
            "idUtilisateurTag" => $offre->getIdutilisateur(),
            "dateCreationTag" => $offre->getDateCreation(),
            "descriptionTag" => $offre->getDescription()
        );

        $pdoStatement->execute($values);

        if ($offre->getStatut() == "en attente") {
            $emails = (new StaffRepository([]))->getManagersEmail();
            MailRepository::send_mail($emails, "Nouvelle offre", '
 <div>
 <p>L\'entreprise ' . Application::getUser()->attributes()["nom"] . ' viens de créer une nouvelle offre</p>
 <a href="' . HOST . '/offres/' . $id . '">Voir l\'offre</a>
 </div>');
        }

        if ($typeStage != null) {
            $sql = "INSERT INTO OffreStage VALUES (:idOffreTag)";
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = array(
                "idOffreTag" => $id,
            );
            $pdoStatement->execute($values);
        }
        if ($typeAlternance != null) {
            $sql = "INSERT INTO OffreAlternance VALUES (:idOffreTag, :distancielTag)";
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = array(
                "idOffreTag" => $id,
                "distancielTag" => $distanciel,
            );
            $pdoStatement->execute($values);
        }
        return true;
    }

    public static function updateOffre(Offre $offre, ?float $distanciel)
    {
        $sql = "UPDATE Offre SET duree=:dureeTag, thematique=:thematiqueTag, sujet=:sujetTag, nbjourtravailhebdo=:nbJourTravailHebdoTag, nbheuretravailhebdo=:nbHeureTravailHebdoTag, gratification=:gratificationTag, avantagesnature=:avantageNatureTag, datedebut=:dateDebutTag, datefin=:dateFinTag, anneevisee=:anneeViseeTag, annee=:idAnneeTag, idutilisateur=:idUtilisateurTag, description=:descriptionTag, statut=:statutTag WHERE idoffre=:idOffreTag";
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "idOffreTag" => $offre->getIdOffre(),
            "dureeTag" => $offre->getDuree(),
            "thematiqueTag" => $offre->getThematique(),
            "sujetTag" => $offre->getSujet(),
            "nbJourTravailHebdoTag" => $offre->getNbjourtravailhebdo(),
            "nbHeureTravailHebdoTag" => $offre->getNbHeureTravailHebdo(),
            "gratificationTag" => $offre->getGratification(),
            "avantageNatureTag" => $offre->getAvantageNature(),
            "dateDebutTag" => $offre->getDateDebut(),
            "dateFinTag" => $offre->getDateFin(),
            "anneeViseeTag" => $offre->getAnneeVisee(),
            "idAnneeTag" => $offre->getAnnee(),
            "idUtilisateurTag" => $offre->getIdutilisateur(),
            "descriptionTag" => $offre->getDescription(),
            "statutTag" => $offre->getStatut(),
        );
        try {
            $pdoStatement->execute($values);
        } catch (PDOException $e) {
            return false;
        }
        if ($distanciel != null) {
            $sql = "UPDATE OffreAlternance SET jourDistanciel=:alternanceTag WHERE idOffre=:idOffreTag";
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
        } catch (PDOException) {
            return false;
        }
        return true;
    }
}
