<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Soutenance;
use Exception;

class SoutenanceRepository extends AbstractRepository
{

    private static string $table = "soutenancesvue";

    /**
     * @return Soutenance[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllSoutenances(): array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . self::$table);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = new Soutenance($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @return Soutenance[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllSoutenancesByIdEtudiant(int $idEtudiant): array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . self::$table . " WHERE idetudiant = " . $idEtudiant);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = new Soutenance($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @return Soutenance[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllSoutenancesByIdTuteurEntreprise(int $idTuteurEntreprise): array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . self::$table . " WHERE idtuteurentreprise = " . $idTuteurEntreprise);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = new Soutenance($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @return Soutenance[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllSoutenancesByIdTuteurProf(int $idTuteurProf): array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . self::$table . " WHERE idtuteurprof = " . $idTuteurProf . " OR idprof = " . $idTuteurProf);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = new Soutenance($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @throws ServerErrorException
     */
    public static function imTheEtudiant($id, $getNumConvention): bool
    {
        return self::Fetch("SELECT * FROM " . self::$table . " WHERE idetudiant = :id AND numconvention = :numconvention", [
                'id' => $id,
                'numconvention' => $getNumConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function imOneOfTheTutor($id, $getNumConvention): bool
    {
        return self::imTheTuteurProf($id, $getNumConvention) || self::imTheTuteurEntreprise($id, $getNumConvention);
    }

    /**
     * @throws ServerErrorException
     */
    public static function imTheTuteurProf($id, $getNumConvention): bool
    {
        return self::Fetch("SELECT * FROM " . self::$table . " WHERE idtuteurprof = :id AND numconvention = :numconvention", [
                'id' => $id,
                'numconvention' => $getNumConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function imTheTuteurEntreprise($id, $getNumConvention): bool
    {
        return self::Fetch("SELECT * FROM " . self::$table . " WHERE idtuteurentreprise = :id AND numconvention = :numconvention", [
                'id' => $id,
                'numconvention' => $getNumConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function imTheJury($id, $getNumConvention): bool
    {
        return self::Fetch("SELECT * FROM " . self::$table . " WHERE idprof = :id AND numconvention = :numconvention", [
                'id' => $id,
                'numconvention' => $getNumConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getSoutenanceByNumConvention(mixed $numConvention): ?Soutenance
    {
        $result = self::Fetch("SELECT * FROM soutenancesvue WHERE numconvention = :numconvention", [
            'numconvention' => $numConvention
        ]);
        return $result ? new Soutenance($result) : null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function createSoutenance(?array $array): void
    {
        $debut_soutenance = date('Y-m-d H:i:s', strtotime($array['debut_soutenance']));
        $fin_soutenance = date('Y-m-d H:i:s', strtotime($array['fin_soutenance']));
        $sql = "INSERT INTO soutenances(numconvention, idtuteurprof, idtuteurentreprise, debut_soutenance, fin_soutenance) VALUES (:numconvention, :idtuteurprof, :idtuteurentreprise, :debut_soutenance, :fin_soutenance)";
        self::Execute($sql, [
            'numconvention' => $array['numconvention'],
            'idtuteurprof' => $array['idtuteurprof'],
            'idtuteurentreprise' => $array['idtuteurentreprise'],
            'debut_soutenance' => $debut_soutenance,
            'fin_soutenance' => $fin_soutenance
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfSoutenanceExist(int $numConvention): bool
    {
        return self::Fetch("SELECT numconvention FROM " . self::$table . " WHERE numconvention = :numconvention", [
                'numconvention' => $numConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function seProposerCommeJury(int $idprof, int $numConvention): void
    {
        $sql = "UPDATE soutenances SET idprof = :idprof WHERE numconvention = :numconvention";
        self::Execute($sql, [
            'idprof' => $idprof,
            'numconvention' => $numConvention
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfJuryExist(int $idprof, int $numConvention): bool
    {
        return self::Fetch("SELECT idsoutenance FROM " . self::$table . " WHERE idprof = :idprof AND numconvention = :numconvention", [
                'idprof' => $idprof,
                'numconvention' => $numConvention
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfImTheTuteurProf(int $idprof, int $numConvention): bool
    {
        return self::Fetch("SELECT idsoutenance FROM " . self::$table . " WHERE idtuteurprof = :idprof AND numconvention = :numconvention", [
                'idprof' => $idprof,
                'numconvention' => $numConvention
            ]) !== null;
    }

    /**
     * @throws Exception
     */
    protected function construireDepuisTableau(array $dataObjectFormatTableau): Soutenance
    {
        return new Soutenance($dataObjectFormatTableau);
    }

    protected function getNomColonnes(): array
    {
        return [
            "id_soutenance",
            "num_convention",
            "id_tuteur_prof",
            "id_tuteur_enterprise",
            "id_professeur",
            "date_soutenance"
        ];
    }

    protected function getNomTable(): string
    {
        return "soutenance";
    }
}
