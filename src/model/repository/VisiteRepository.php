<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Visite;
use Exception;

class VisiteRepository extends AbstractRepository
{
    /**
     * @throws ServerErrorException
     */
    public static function update(int $numConvention, string $start, string $end): void
    {
        self::Execute("update visite set debut_visite=?, fin_visite= ? where num_convention=?", [date('Y-m-d H:i:s', strtotime($start)), date('Y-m-d H:i:s', strtotime($end)), $numConvention]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfVisiteExist(int $numConvention): bool
    {
        return self::FetchAssoc("select num_convention from visite where num_convention=?", [$numConvention]) != null;
    }

    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getByStudentId(int $idEtudiant): Visite|null
    {
        $convention = ConventionRepository::getIdByStudent($idEtudiant);
        if (!$convention) return null;
        $sql = "SELECT * FROM visite WHERE num_convention = ?";
        $result = self::FetchAssoc($sql, [$convention]);
        return $result ? new Visite($result) : null;
    }

    /**
     * @return Visite[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllByEnterpriseTutorId(int $idTuteurPro): array
    {
        $sql = "SELECT v.* FROM supervise s JOIN convention c ON c.idoffre = s.idoffre JOIN visite v ON v.num_convention = c.numconvention WHERE s.idtuteurentreprise=?";
        $result = self::FetchAllAssoc($sql, [$idTuteurPro]) ?? [];
        $visites = [];
        foreach ($result as $row)
            $visites[] = new Visite($row);
        return $visites;
    }

    /**
     * @return Visite[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllByUniversityTutorId(int $idTuteurUniv): array
    {
        $sql = "SELECT v.* FROM supervise s JOIN convention c ON c.idoffre = s.idoffre JOIN visite v ON v.num_convention = c.numconvention WHERE s.idutilisateur=?";
        $result = self::FetchAllAssoc($sql, [$idTuteurUniv]) ?? [];
        $visites = [];
        foreach ($result as $row)
            $visites[] = new Visite($row);
        return $visites;
    }

    /**
     * @return Visite[]
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllVisites(): array
    {
        $result = self::FetchAllAssoc("SELECT * FROM visite") ?? [];
        $visites = [];
        foreach ($result as $row)
            $visites[] = new Visite($row);
        return $visites;
    }

    /**
     * @throws ServerErrorException
     */
    public static function createVisite(string $debut, string $fin, int $numConvention): void
    {
        self::Execute("insert into visite (debut_visite, fin_visite, num_convention) values (?,?,?);", [date('Y-m-d H:i:s', strtotime($debut)), date('Y-m-d H:i:s', strtotime($fin)), $numConvention]);
    }

    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public static function getAllByEnterpriseId(int $id): array
    {
        $sql = "SELECT v.* FROM convention c JOIN visite v ON v.num_convention = c.numconvention JOIN Offre o ON o.idoffre=c.idoffre WHERE o.idutilisateur=?";
        $result = self::FetchAllAssoc($sql, [$id]) ?? [];
        $visites = [];
        foreach ($result as $row)
            $visites[] = new Visite($row);
        return $visites;
    }


    /**
     * @throws ServerErrorException
     */
    public function getByConvention(int $numConvention): ?array
    {
        return self::Fetch("SELECT * FROM visite WHERE num_convention=?", [$numConvention]);
    }

    /**
     * @throws Exception
     */
    protected function construireDepuisTableau(array $dataObjectFormatTableau): Visite
    {
        return new Visite($dataObjectFormatTableau);
    }

    protected function getNomColonnes(): array
    {
        return [
            "id_etudiant",
            "id_tuteur_univ",
            "id_tuteur_pro",
            "debut_visite",
            "fin_visite",
        ];
    }

    protected function getNomTable(): string
    {
        return "visite";
    }
}
