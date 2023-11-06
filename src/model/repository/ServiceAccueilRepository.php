<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\ServiceAccueil;

class ServiceAccueilRepository extends AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    public function getFullByEntreprise(int $identreprise): ?array
    {
        $sql = "SELECT * FROM ServiceAccueil WHERE idEntreprise = :idEntreprise";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idEntreprise", $identreprise);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        $serviceAccueil = [];
        foreach ($result as $row) {
            $serviceAccueil[$row['nomservice']] = $row['nomservice'];
        }
        return $serviceAccueil;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): ServiceAccueil
    {
        return new ServiceAccueil(
            $dataObjectFormatTableau['idService'],
            $dataObjectFormatTableau['nomService'],
            $dataObjectFormatTableau['adresse'],
            $dataObjectFormatTableau['adresseCedex'],
            $dataObjectFormatTableau['adresseResidence'],
            $dataObjectFormatTableau['idVille'],
            $dataObjectFormatTableau['idEntreprise']
        );
    }

    protected function getNomTable(): string
    {
        return "ServiceAccueil";
    }

    protected function getNomColonnes(): array
    {
        return [
            "idService",
            "nomService",
            "adresse",
            "adresseCedex",
            "adresseResidence",
            "idVille",
            "idEntreprise"
        ];
    }
}