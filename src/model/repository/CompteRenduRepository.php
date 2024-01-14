<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
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
        return self::Fetch($sql, [
                "idtuteur" => $idtuteur,
                "idetudiant" => $idetudiant,
                "numconvention" => $numconvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getCommentaires(mixed $numConvention, int $studentId): bool|array
    {
        $sql = "SELECT * FROM compterendus WHERE numconvention = :numconvention AND idetudiant = :idetudiant";
        return self::FetchAll($sql, [
            "numconvention" => $numConvention,
            "idetudiant" => $studentId
        ]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduExist(mixed $numconvention): bool
    {
        $sql = "SELECT numconvention FROM compterendus WHERE numconvention = :numconvention";
        return self::Fetch($sql, [
                "numconvention" => $numconvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateCompteRenduProf(mixed $numconvention, mixed $compteRendu): void
    {
        $sql = "UPDATE compterendus SET commentaireprof = :commentaireprof WHERE numconvention = :numconvention";
        self::Execute($sql, [
            "numconvention" => $numconvention,
            "commentaireprof" => $compteRendu
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateCompteRenduEntreprise(mixed $numconvention, mixed $compteRendu): void
    {
        $sql = "UPDATE compterendus SET commentaireentreprise = :commentaireentreprise WHERE numconvention = :numconvention";
        self::Execute($sql, [
            "numconvention" => $numconvention,
            "commentaireentreprise" => $compteRendu
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function createCompteRenduAsProf(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu): void
    {
        $sql = "INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentaireprof) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentaireprof)";
        self::Execute($sql, [
            "idtuteurprof" => $idtuteurprof,
            "idtuteurentreprise" => $idtuteurentreprise,
            "idetudiant" => $idetudiant,
            "numconvention" => $numconvention,
            "commentaireprof" => $compteRendu
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function createCompteRenduAsEntreprise(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu): void
    {
        $sql = "INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentaireentreprise) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentaireentreprise)";
        self::Execute($sql, [
            "idtuteurprof" => $idtuteurprof,
            "idtuteurentreprise" => $idtuteurentreprise,
            "idetudiant" => $idetudiant,
            "numconvention" => $numconvention,
            "commentaireentreprise" => $compteRendu
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfImOneOfTheTutors(mixed $numconvention, mixed $idtuteur): ?array
    {
        $sql = "SELECT * FROM compterendus WHERE numconvention = :numconvention AND (idtuteurprof = :idtuteur OR idtuteurentreprise = :idtuteur)";
        return self::Fetch($sql, [
            'numconvention' => $numconvention,
            'idtuteur' => $idtuteur
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduProfExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentaireprof FROM compterendus WHERE numconvention = :numconvention";
        return self::Fetch($sql, [
                "numconvention" => $getNumConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduEntrepriseExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentaireentreprise FROM compterendus WHERE numconvention = :numconvention";
        $data = self::Fetch($sql, [
            "numconvention" => $getNumConvention
        ]) ?? [];
        return $data !== null && $data['commentaireentreprise'] !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduSoutenanceProfExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentairesoutenanceprof FROM compterendus WHERE numconvention = :numconvention";
        $data = self::Fetch($sql, [
            "numconvention" => $getNumConvention
        ]) ?? [];
        return $data !== null && isset($data['commentairesoutenanceprof']);
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduSoutenanceEntrepriseExist(int $getNumConvention): bool
    {
        $sql = "SELECT commentairesoutenanceentreprise FROM compterendus WHERE numconvention = :numconvention";
        $data = self::Fetch($sql, [
            "numconvention" => $getNumConvention
        ]) ?? [];
        return $data !== null && $data['commentairesoutenanceentreprise'] !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkIfCompteRenduSoutenanceExist(mixed $numconvention): bool
    {
        $sql = "SELECT commentairesoutenanceprof, commentairesoutenanceentreprise FROM compterendus WHERE numconvention = :numconvention";
        $data = self::Fetch($sql, [
            "numconvention" => $numconvention
        ]) ?? [];
        return $data !== null && $data['commentairesoutenanceprof'] !== null && $data['commentairesoutenanceentreprise'] !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateCompteRenduSoutenanceProf(mixed $numconvention, mixed $compteRenduSoutenance): void
    {
        $sql = "UPDATE compterendus SET commentairesoutenanceprof = :commentairesoutenanceprof WHERE numconvention = :numconvention";
        self::Execute($sql, [
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
        self::Execute($sql, [
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
        self::Execute($sql, [
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
        self::Execute($sql, [
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
