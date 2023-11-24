<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Visite;

class VisiteRepository extends AbstractRepository
{

    /**
     * @return Visite[]
     * @throws ServerErrorException
     */
    public static function getAllByStudentId(int $idEtudiant): array
    {
        try {
            $sql = "SELECT * FROM visite WHERE id_etudiant = :idEtudiant";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->bindValue(":idEtudiant", $idEtudiant);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $visites = [];
            foreach ($result as $row)
                $visites[] = new Visite($row);
            return $visites;
        } catch (\Exception $e) {
            throw new ServerErrorException();
        }
    }

    /**
     * @return Visite[]
     * @throws ServerErrorException
     */
    public static function getAllByEnterpriseTutorId(int $idTuteurPro): array
    {
        try {
            $sql = "SELECT * FROM visite WHERE id_tuteur_pro = :idTuteurPro";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->bindValue(":idTuteurPro", $idTuteurPro);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $visites = [];
            foreach ($result as $row)
                $visites[] = new Visite($row);
            return $visites;
        } catch (\Exception $e) {
            throw new ServerErrorException();
        }
    }

    /**
     * @return Visite[]
     * @throws ServerErrorException
     */
    public static function getAllByUniversityTutorId(int $idTuteurUniv): array
    {
        try {
            $sql = "SELECT * FROM visite WHERE id_tuteur_univ = :idTuteurUniv";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->bindValue(":idTuteurUniv", $idTuteurUniv);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $visites = [];
            foreach ($result as $row)
                $visites[] = new Visite($row);
            return $visites;
        } catch (\Exception $e) {
            throw new ServerErrorException();
        }
    }

    /**
     * @return Visite[]
     * @throws ServerErrorException
     */
    public static function getAllVisites(): array
    {
        try {
            $sql = "SELECT * FROM visite";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $visites = [];
            foreach ($result as $row)
                $visites[] = new Visite($row);
            return $visites;
        } catch (\Exception $e) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws \Exception
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