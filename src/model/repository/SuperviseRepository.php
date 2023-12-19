<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Supervise;
use Exception;

class SuperviseRepository extends AbstractRepository
{
    /**
     * @throws ServerErrorException
     */
    public static function getByConvention(int $numConvention): Supervise|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT s.* FROM supervise s
                JOIN convention c ON c.idoffre = s.idoffre
                WHERE c.numconvention=?");
            $statement->execute([$numConvention]);
            $data = $statement->fetch();
            if (!$data) return null;
            return new Supervise($data);
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    protected function getNomTable(): string
    {
        return "supervise";
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Supervise
    {
        return new Supervise($dataObjectFormatTableau);
    }

    protected function getNomColonnes(): array
    {
        return [];
    }
}