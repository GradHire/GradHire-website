<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\SimulationPstage;

class SimulationPstageRepository extends AbstractRepository
{
    public function getFullByNomFichier(string $nomFichier): ?AbstractDataObject
    {
        $sql = "SELECT * FROM SimulationPstage WHERE nomFichier = :nomFichier";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":nomFichier", $nomFichier);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if (!$result) {
            return null;
        }
        return $this->construireDepuisTableau($result[0]);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject
    {
        return new SimulationPstage(
            $dataObjectFormatTableau['idSimulation'],
            $dataObjectFormatTableau['nomFichier'],
            $dataObjectFormatTableau['statut'],
            $dataObjectFormatTableau['idEtudiant']
        );
    }

    public function create(mixed $nomFichier, mixed $idEtudiant, mixed $statut)
    {
        $sql = "INSERT INTO SimulationPstage(nomFichier,statut ,idEtudiant) VALUES (:nomFichier,:statut ,:idEtudiant)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":nomFichier", $nomFichier);
        $stmt->bindValue(":idEtudiant", $idEtudiant);
        $stmt->bindValue(":statut", $statut);
        $stmt->execute();
    }

    protected function getNomTable(): string
    {
        return "SimulationPstage";
    }

    protected function getNomColonnes(): array
    {
        return ["idSimulation", "nomFichier", "statut", "idEtudiant"];
    }
}