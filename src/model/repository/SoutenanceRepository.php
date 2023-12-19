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
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$table . " WHERE idetudiant = :id AND numconvention = :numconvention");
        $sql->execute([
            'id' => $id,
            'numconvention' => $getNumConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
    }

    /**
     * @throws ServerErrorException
     */
    public static function imTheTuteurEntreprise($id, $getNumConvention): bool
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$table . " WHERE idtuteurentreprise = :id AND numconvention = :numconvention");
        $sql->execute([
            'id' => $id,
            'numconvention' => $getNumConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
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
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$table . " WHERE idtuteurprof = :id AND numconvention = :numconvention");
        $sql->execute([
            'id' => $id,
            'numconvention' => $getNumConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
    }

    /**
     * @throws ServerErrorException
     */
    public static function imTheJury($id, $getNumConvention): bool
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$table . " WHERE idprof = :id AND numconvention = :numconvention");
        $sql->execute([
            'id' => $id,
            'numconvention' => $getNumConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
    }

    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getSoutenanceByNumConvention(mixed $numConvention): ?Soutenance
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM soutenancesvue WHERE numconvention = :numconvention");
        $sql->execute([
            'numconvention' => $numConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return null;
        else
            return new Soutenance($result);
    }

    /**
     * @throws ServerErrorException
     */
    public static function createSoutenance(?array $array): void
    {
        $debut_soutenance = date('Y-m-d H:i:s', strtotime($array['debut_soutenance']));
        $fin_soutenance = date('Y-m-d H:i:s', strtotime($array['fin_soutenance']));
        $sql = "INSERT INTO soutenances(numconvention, idtuteurprof, idtuteurentreprise, debut_soutenance, fin_soutenance) VALUES (:numconvention, :idtuteurprof, :idtuteurentreprise, :debut_soutenance, :fin_soutenance)";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
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
        $sql = Database::get_conn()->prepare("SELECT idsoutenance FROM " . self::$table . " WHERE numconvention = :numconvention");
        $sql->execute([
            'numconvention' => $numConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
    }

    /**
     * @throws ServerErrorException
     */
    public static function seProposerCommeJury(int $idprof, int $numConvention): void
    {
        try {
            $sql = "UPDATE soutenances SET idprof = :idprof WHERE numconvention = :numconvention";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idprof' => $idprof,
                'numconvention' => $numConvention
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfJuryExist(int $idprof, int $numConvention): bool
    {
        $sql = Database::get_conn()->prepare("SELECT idsoutenance FROM " . self::$table . " WHERE idprof = :idprof AND numconvention = :numconvention");
        $sql->execute([
            'idprof' => $idprof,
            'numconvention' => $numConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfImTheTuteurProf(int $idprof, int $numConvention): bool
    {
        $sql = Database::get_conn()->prepare("SELECT idsoutenance FROM " . self::$table . " WHERE idtuteurprof = :idprof AND numconvention = :numconvention");
        $sql->execute([
            'idprof' => $idprof,
            'numconvention' => $numConvention
        ]);
        $result = $sql->fetch();
        if ($result == null)
            return false;
        else
            return true;
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