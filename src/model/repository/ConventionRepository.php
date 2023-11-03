<?php

namespace app\src\model\repository;
use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Convention;

class ConventionRepository extends AbstractRepository
{
    protected static string $table = "Convention";



    /**
     * @throws ServerErrorException
     */
    public function getAll(): array
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table);
            $statement->execute();
            $data = $statement->fetchAll();
            $conventions = [];
            foreach ($data as $row) {
                $conventions[] = $this->construireDepuisTableau($row);
            }
            return $conventions;
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération des conventions", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getById(int $id) : ?Convention
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table . " WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $data = $statement->fetch();
            if (!$data) return null;
            return $this->construireDepuisTableau($data);
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerPedagogiquement(int $id) : void
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 1 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la validation pédagogique de la convention", 500, $e);
        }
    }

    public function construireDepuisTableau(array $dataObjectFormatTableau): Convention
    {
        return new Convention(
            $dataObjectFormatTableau["numconvention"],
            $dataObjectFormatTableau["origineconvention"],
            $dataObjectFormatTableau["conventionvalidee"],
            $dataObjectFormatTableau["conventionvalideepedagogiquement"],
            $dataObjectFormatTableau["datemodification"],
            $dataObjectFormatTableau["datecreation"],
            $dataObjectFormatTableau["idsignataire"],
            $dataObjectFormatTableau["idinterruption"],
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["idoffre"],
            $dataObjectFormatTableau["commentaire"]
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidatePedagogiquement(mixed $id): void
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 0 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de l'archivage pédagogique de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidate(mixed $id)
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalidee = 0 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de l'archivage de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function valider(mixed $id)
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalidee = 1 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la validation de la convention", 500, $e);
        }
    }


    protected function getNomTable(): string
    {
        return static::$table;
    }

    protected function getNomColonnes(): array
    {
        return [
            "numconvention",
            "origineconvention",
            "conventionvalidee",
            "conventionvalideepedagogiquement",
            "datemodification",
            "datecreation",
            "idsignataire",
            "idinterruption",
            "idutilisateur",
            "idoffre",
            "commentaire"
        ];
    }
}