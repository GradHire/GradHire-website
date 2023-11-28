<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Visite;

class VisiteRepository extends AbstractRepository
{
    /**
     * @throws ServerErrorException
     */
    public static function update(int $numConvention, string $start, string $end): void
    {
        try {
            $statement = Database::get_conn()->prepare("update visite set debut_visite=?, fin_visite= ? where num_convention=?");
            $statement->execute([date('Y-m-d H:i:s', strtotime($start)), date('Y-m-d H:i:s', strtotime($end)), $numConvention]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByStudentId(int $idEtudiant): Visite|null
    {
        try {
            $convention = ConventionRepository::getIdByStudent($idEtudiant);
            if (!$convention) return null;
            $sql = "SELECT * FROM visite WHERE num_convention = ?";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->execute([$convention]);
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            if (!$result) return null;
            return new Visite($result);
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
            $sql = "SELECT v.* FROM supervise s JOIN convention c ON c.idoffre = s.idoffre JOIN visite v ON v.num_convention = c.numconvention WHERE s.idtuteurentreprise=?";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->execute([$idTuteurPro]);
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
            $sql = "SELECT v.* FROM supervise s JOIN convention c ON c.idoffre = s.idoffre JOIN visite v ON v.num_convention = c.numconvention WHERE s.idutilisateur=?";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->execute([$idTuteurUniv]);
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
     * @throws ServerErrorException
     */
    public static function createVisite(string $debut, string $fin, int $numConvention)
    {
        try {
            $statement = Database::get_conn()->prepare("insert into visite (debut_visite, fin_visite, num_convention) values (?,?,?);");
            $statement->execute([date('Y-m-d H:i:s', strtotime($debut)), date('Y-m-d H:i:s', strtotime($fin)), $numConvention]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public static function createCompteRendu(int $idTuteur, int $idEtudiant, int $numConvention, string $compteRendu)
    {
        $sql = "INSERT INTO compterendus (idtuteur, idetudiant, numconvention, commentaire) VALUES (:idtuteur, :idetudiant, :numconvention, :commentaire)";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'idtuteur' => $idTuteur,
            'idetudiant' => $idEtudiant,
            'numconvention' => $numConvention,
            'commentaire' => $compteRendu
        ]);
    }

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
    public function getByConvention(int $numConvention)
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM visite WHERE num_convention=?");
            $statement->execute([$numConvention]);
            return $statement->fetch();
        } catch (\Exception) {
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