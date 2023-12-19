<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\core\lib\StackTrace;
use app\src\model\dataObject\CompteRendu;
use Exception;

class CompteRenduRepository extends AbstractRepository
{


    /**
     * @throws ServerErrorException
     */
    public static function checkIfAlreadyCompteRendu(int $idtuteur, int $idetudiant, int $numconvention): bool
    {
        $sql = "SELECT idcompterendu FROM compterendus WHERE idtuteur = :idtuteur AND idetudiant = :idetudiant AND numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'idtuteur' => $idtuteur,
            'idetudiant' => $idetudiant,
            'numconvention' => $numconvention
        ]);
        $result = $requete->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getCommentaires(mixed $numConvention, int $studentId): bool|array
    {
        $sql = "SELECT * FROM compterendus WHERE numconvention = :numconvention AND idetudiant = :idetudiant";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numConvention,
            'idetudiant' => $studentId
        ]);
        return $requete->fetchAll();
    }

    /**
     */
    public static function checkIfCompteRenduExist(mixed $numconvention): bool
    {
        try {
            $sql = "SELECT numconvention FROM compterendus WHERE numconvention = :numconvention";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'numconvention' => $numconvention
            ]);
            $result = $requete->fetch();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            StackTrace::print($e);
        }
        return false;
    }

    public static function updateCompteRenduProf(mixed $numconvention, mixed $compteRendu): void
    {
        try {
            $sql = "UPDATE compterendus SET commentaireprof = :commentaireprof WHERE numconvention = :numconvention";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'numconvention' => $numconvention,
                'commentaireprof' => $compteRendu
            ]);
        }
        catch (Exception $e) {
            StackTrace::print($e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateCompteRenduEntreprise(mixed $numconvention, mixed $compteRendu): void
    {
        $sql = "UPDATE compterendus SET commentaireentreprise = :commentaireentreprise WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'commentaireentreprise' => $compteRendu
        ]);
    }

    public static function createCompteRenduAsProf(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu): void
    {
        try {
            $sql = "INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentaireprof) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentaireprof)";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idtuteurprof' => $idtuteurprof,
                'idtuteurentreprise' => $idtuteurentreprise,
                'idetudiant' => $idetudiant,
                'numconvention' => $numconvention,
                'commentaireprof' => $compteRendu
            ]);
        } catch (Exception $e) {
            StackTrace::print($e);
        }
    }

    public static function createCompteRenduAsEntreprise(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu): void
    {
        try {
            $sql = "INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentaireentreprise) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentaireentreprise)";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idtuteurprof' => $idtuteurprof,
                'idtuteurentreprise' => $idtuteurentreprise,
                'idetudiant' => $idetudiant,
                'numconvention' => $numconvention,
                'commentaireentreprise' => $compteRendu
            ]);
        } catch (Exception $e) {
            StackTrace::print($e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfImOneOfTheTutors(mixed $numconvention, mixed $idtuteur)
    {
        $sql = "SELECT * FROM compterendus WHERE numconvention = :numconvention AND (idtuteurprof = :idtuteur OR idtuteurentreprise = :idtuteur)";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'idtuteur' => $idtuteur
        ]);
        return $requete->fetch();
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduProfExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentaireprof FROM compterendus WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $getNumConvention
        ]);
        $result = $requete->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduEntrepriseExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentaireentreprise FROM compterendus WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $getNumConvention
        ]);
        $result = $requete->fetch();
        if ($result['commentaireentreprise'] != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduSoutenanceProfExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentairesoutenanceprof FROM compterendus WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $getNumConvention
        ]);
        $result = $requete->fetch();
        if ($result['commentairesoutenanceprof'] != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduSoutenanceEntrepriseExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentairesoutenanceentreprise FROM compterendus WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $getNumConvention
        ]);
        $result = $requete->fetch();
        if ($result['commentairesoutenanceentreprise'] != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduSoutenanceExist(mixed $numconvention): bool
    {
        $sql = "SELECT commentairesoutenanceprof, commentairesoutenanceentreprise FROM compterendus WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention
        ]);
        $result = $requete->fetch();
        if ($result['commentairesoutenanceprof'] != null && $result['commentairesoutenanceentreprise'] != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateCompteRenduSoutenanceProf(mixed $numconvention, mixed $compteRenduSoutenance): void
    {
        $sql = "UPDATE compterendus SET commentairesoutenanceprof = :commentairesoutenanceprof WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'commentairesoutenanceprof' => $compteRenduSoutenance
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function createCompteRenduSoutenanceAsProf(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRenduSoutenance): void
    {
        $sql = "INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentairesoutenanceprof) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentairesoutenanceprof)";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'idtuteurprof' => $idtuteurprof,
            'idtuteurentreprise' => $idtuteurentreprise,
            'idetudiant' => $idetudiant,
            'numconvention' => $numconvention,
            'commentairesoutenanceprof' => $compteRenduSoutenance
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateCompteRenduSoutenanceEntreprise(mixed $numconvention, mixed $compteRenduSoutenance): void
    {
        $sql = "UPDATE compterendus SET commentairesoutenanceentreprise = :commentairesoutenanceentreprise WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'commentairesoutenanceentreprise' => $compteRenduSoutenance
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function createCompteRenduSoutenanceAsEntreprise(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRenduSoutenance): void
    {
        $sql = "INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentairesoutenanceentreprise) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentairesoutenanceentreprise)";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'idtuteurprof' => $idtuteurprof,
            'idtuteurentreprise' => $idtuteurentreprise,
            'idetudiant' => $idetudiant,
            'numconvention' => $numconvention,
            'commentairesoutenanceentreprise' => $compteRenduSoutenance
        ]);
    }

    /**
     * @throws Exception
     */
    protected function construireDepuisTableau(array $dataObjectFormatTableau): CompteRendu
    {
        return new CompteRendu($dataObjectFormatTableau);
    }

    protected function getNomColonnes(): array
    {
        return [
            "numconvention",
            "idetudiant",
            "idtuteurprof",
            "commentaireprof",
            "idtuteurentreprise",
            "commentaireentreprise",
        ];
    }

    protected function getNomTable(): string
    {
        return "compterendus";
    }
}