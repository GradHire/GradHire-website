<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Soutenance;

class SoutenanceRepository extends AbstractRepository
{

    private static string $table = "soutenancesvue";

    /**
     * @return Soutenance[]
     * @throws ServerErrorException
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
     */
    public static function getAllSoutenancesByIdTuteurProf(int $idTuteurProf): array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . self::$table . " WHERE idtuteurprof = " . $idTuteurProf);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = new Soutenance($dataObjectFormatTableau);
        return $dataObjects;
    }

    public function getAll(): ?array
    {
        return parent::getAll();
    }

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