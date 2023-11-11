<?php

namespace app\src\model\repository;

use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Signataire;

class SignataireRepository extends AbstractRepository
{
    public function getFullByEntreprise(int $identreprise): ?array
    {
        $sql = "SELECT * FROM Signataire WHERE idEntreprise = :idEntreprise";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idEntreprise", $identreprise);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        $signataires = [];
        foreach ($result as $row) {
            $signataires[$row['idSignataire']] = $this->construireDepuisTableau($row);
        }
        return $signataires;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject
    {
        return new Signataire(
            $dataObjectFormatTableau['idSignataire'],
            $dataObjectFormatTableau['nomSignataire'],
            $dataObjectFormatTableau['prenomSignataire'],
            $dataObjectFormatTableau['fonctionSignataire'],
            $dataObjectFormatTableau['mailSignataire'],
            $dataObjectFormatTableau['idEntreprise']);
    }

    public function create(mixed $nomSignataire, mixed $prenomSignataire, mixed $fonctionSignataire, mixed $mailSignataire, mixed $idEntreprise)
    {
        $sql = "CALL creerSignataire(:nomSignataire, :prenomSignataire, :mailSignataire, :fonctionSignataire, :idEntreprise)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":nomSignataire", $nomSignataire);
        $stmt->bindValue(":prenomSignataire", $prenomSignataire);
        $stmt->bindValue(":fonctionSignataire", $fonctionSignataire);
        $stmt->bindValue(":mailSignataire", $mailSignataire);
        $stmt->bindValue(":idEntreprise", $idEntreprise);
        $stmt->execute();
    }

    protected function getNomTable(): string
    {
        return "Signataire";
    }

    protected function getNomColonnes(): array
    {
        return ["idSignataire", "nomSignataire", "prenomSignataire", "fonctionSignataire", "mailSignataire", "idEntreprise"];
    }
}