<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Avis;
use app\src\model\repository\AbstractRepository;

class AvisRepository extends AbstractRepository{

    private static string $nomTable = "avis";

    public static function checkIfAvisPosted($getIdutilisateur, int $id): bool
    {
        $sql = "SELECT idavis FROM avis WHERE idutilisateur = :idutilisateur AND identreprise = :identreprise";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([
            "idutilisateur" => $id,
            "identreprise" => $getIdutilisateur
        ]);
        $result = $stmt->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function createAvis(mixed $identreprise, int $id, mixed $Avis)
    {
        $sql = "INSERT INTO avis (identreprise, idutilisateur, avis) VALUES (:identreprise, :idutilisateur, :avis)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([
            "identreprise" => $identreprise,
            "idutilisateur" => $id,
            "avis" => $Avis
        ]);
    }

    public static function getAvisById(mixed $identreprise, int $idutilisateur)
    {
        $sql = "SELECT avis FROM avis WHERE identreprise = :identreprise AND idutilisateur = :idutilisateur";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([
            "identreprise" => $identreprise,
            "idutilisateur" => $idutilisateur
        ]);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    public static function updateAvis(mixed $identreprise, int $id, mixed $Avis)
    {
        $sql = "UPDATE avis SET avis = :avis WHERE identreprise = :identreprise AND idutilisateur = :idutilisateur";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([
            "identreprise" => $identreprise,
            "idutilisateur" => $id,
            "avis" => $Avis
        ]);
    }

    public static function getAvisEntreprise(int $getIdutilisateur)
    {
        $sql = "SELECT avis FROM avis WHERE identreprise = :identreprise";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([
            "identreprise" => $getIdutilisateur
        ]);
        $result = $stmt->fetchAll();
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }


    protected function getNomTable(): string
    {
        return self::$nomTable;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Avis
    {
        return new Avis($dataObjectFormatTableau);
    }

    protected function getNomColonnes(): array
    {
        return array("idavis", "identreprise", "idutilisateur", "commentaire");
    }
}