<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Avis;

class AvisRepository extends AbstractRepository
{

    private static string $nomTable = "avis";

    /**
     * @throws ServerErrorException
     */
    public static function checkIfAvisPosted($getIdutilisateur, int $id): bool
    {
        $sql = "SELECT idavis FROM avis WHERE idutilisateur = :idutilisateur AND identreprise = :identreprise";
        return self::Fetch($sql, [
                "idutilisateur" => $getIdutilisateur,
                "identreprise" => $id
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function createAvis(mixed $identreprise, int $id, mixed $Avis, int $visible): void
    {
        $sql = "INSERT INTO avis (identreprise, idutilisateur, avis, private) VALUES (:identreprise, :idutilisateur, :avis, :private)";
        self::Execute($sql, [
            "identreprise" => $identreprise,
            "idutilisateur" => $id,
            "avis" => $Avis,
            "private" => $visible
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getAvisById(mixed $identreprise, int $idutilisateur): array
    {
        $sql = "SELECT avis FROM avis WHERE identreprise = :identreprise AND idutilisateur = :idutilisateur";
        return self::Fetch($sql, [
            "identreprise" => $identreprise,
            "idutilisateur" => $idutilisateur
        ]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function updateAvis(mixed $identreprise, int $id, mixed $Avis, int $visible): void
    {
        $sql = "UPDATE avis SET avis = :avis, private = :private WHERE identreprise = :identreprise AND idutilisateur = :idutilisateur";
        self::Execute($sql, [
            "identreprise" => $identreprise,
            "idutilisateur" => $id,
            "avis" => $Avis,
            "private" => $visible
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getAvisEntreprisePriver(int $getIdutilisateur): array
    {
        $sql = "SELECT avis FROM avis WHERE identreprise = :identreprise AND private = 1";
        return self::FetchAll($sql, [
            "identreprise" => $getIdutilisateur
        ]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getAvisEntreprisePublic(int $getIdutilisateur): array
    {
        $sql = "SELECT avis FROM avis WHERE identreprise = :identreprise AND private = 0";
        return self::FetchAll($sql, [
            "identreprise" => $getIdutilisateur
        ]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getPrivate(mixed $identreprise, int $idutilisateur): array
    {
        $sql = "SELECT private FROM avis WHERE identreprise = :identreprise AND idutilisateur = :idutilisateur";
        return self::Fetch($sql, [
            "identreprise" => $identreprise,
            "idutilisateur" => $idutilisateur
        ]) ?? [];
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