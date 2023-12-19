<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\SimulationPstage;
use Exception;
use PDO;

class SimulationPstageRepository extends AbstractRepository
{
    /**
     * @throws ServerErrorException
     */
    public function getNomById(int $id): ?string
    {
        $sql = "SELECT nomFichier FROM SimulationPstage WHERE idSimulation = :id";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if (!$result) {
            return null;
        }
        return $result[0]["nomfichier"];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getStudentId(int $conventionId): int|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT idetudiant FROM hirchytsd.\"conventionValideVue\" WHERE numconvention=?");
            $statement->execute([$conventionId]);
            $data = $statement->fetch();
            if (!$data) return null;
            return $data["idetudiant"];
        } catch (Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération de l'id de l'étudiant", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getFullByNomFichier(string $nomFichier): ?AbstractDataObject
    {
        $sql = "SELECT * FROM SimulationPstage WHERE nomFichier = :nomFichier";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":nomFichier", $nomFichier);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if (!$result) {
            return null;
        }
        return $this->construireDepuisTableau($result[0]);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject
    {
        return new SimulationPstage(
            $dataObjectFormatTableau
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function create(mixed $nomFichier, mixed $idEtudiant, mixed $statut): void
    {
        $sql = "INSERT INTO SimulationPstage(nomFichier,statut ,idEtudiant) VALUES (:nomFichier,:statut ,:idEtudiant)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":nomFichier", $nomFichier);
        $stmt->bindValue(":idEtudiant", $idEtudiant);
        $stmt->bindValue(":statut", $statut);
        $stmt->execute();
    }

    public function getAll(): ?array
    {
        $sql = "Select * from SimulationPstage";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if (!$result) {
            return null;
        }
        $dataObjects = [];
        foreach ($result as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @throws ServerErrorException
     */
    public function updatevalide(mixed $id): void
    {
        $sql = "UPDATE SimulationPstage SET statut = 'Validee' WHERE idSimulation = :id";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }

    /**
     * @throws ServerErrorException
     */
    public function updaterefuse(mixed $id): void
    {
        $sql = "UPDATE SimulationPstage SET statut = 'Refusee' WHERE idSimulation = :id";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdEtudiant(int $id): ?array
    {
        $sql = "SELECT * FROM SimulationPstage WHERE idEtudiant = :id";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if (!$result) {
            return null;
        }
        $dataObjects = [];
        foreach ($result as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @throws ServerErrorException
     */
    public function updateMotif(mixed $id, mixed $motif): void
    {
        $sql = "UPDATE SimulationPstage SET motif = :motif WHERE idSimulation = :id";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":motif", $motif);
        $stmt->execute();
    }

    /**
     * @throws ServerErrorException
     */
    public function getMotifById(mixed $id)
    {
        $sql = "SELECT motif FROM SimulationPstage WHERE idSimulation = :id";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if (!$result) {
            return null;
        }
        return $result[0]["motif"];
    }

    protected function getNomTable(): string
    {
        return "SimulationPstage";
    }

    protected function getNomColonnes(): array
    {
        return ["idsimulation", "nomfichier", "statut", "idetudiant"];
    }
}
