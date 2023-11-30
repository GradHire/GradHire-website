<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\core\lib\StackTrace;
use app\src\model\dataObject\AbstractDataObject;

class CompteRenduRepository extends AbstractRepository
{


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

    public static function getCommentaires(mixed $numConvention, int $studentId)
    {
        $sql = "SELECT * FROM compterendus WHERE numconvention = :numconvention AND idetudiant = :idetudiant";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numConvention,
            'idetudiant' => $studentId
        ]);
        $result = $requete->fetchAll();
        return $result;
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduExist(mixed $numconvention)
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
        } } catch (\Exception $e) {
            StackTrace::print($e);
        }
    }

    public static function updateCompteRenduProf(mixed $numconvention, mixed $compteRendu)
    {
        $sql = "UPDATE compterendus SET commentaireprof = :commentaireprof WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'commentaireprof' => $compteRendu
        ]);
    }

    public static function updateCompteRenduEntreprise(mixed $numconvention, mixed $compteRendu)
    {
        $sql = "UPDATE compterendus SET commentaireentreprise = :commentaireentreprise WHERE numconvention = :numconvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'commentaireentreprise' => $compteRendu
        ]);
    }

    public static function createCompteRenduAsProf(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu)
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
        } catch (\Exception $e) {
            StackTrace::print($e);
        }
    }

    public static function createCompteRenduAsEntreprise(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu)
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
        }catch (\Exception $e) {
            StackTrace::print($e);
        }
    }

    public static function getIfImOneOfTheTutors(mixed $numconvention, mixed $idtuteur)
    {
        $sql = "SELECT * FROM compterendus WHERE numconvention = :numconvention AND (idtuteurprof = :idtuteur OR idtuteurentreprise = :idtuteur)";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'numconvention' => $numconvention,
            'idtuteur' => $idtuteur
        ]);
        $result = $requete->fetch();
        return $result;
    }

    public static function checkIfCompteRenduProfExist(int $getNumConvention)
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

    public static function checkIfCompteRenduEntrepriseExist(int $getNumConvention)
    {
        $sql = "SELECT commentaireentreprise FROM compterendus WHERE numconvention = :numconvention";
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
     * @throws \Exception
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