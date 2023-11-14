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

    public function create(mixed $nomService, mixed $idEntreprise, mixed $voie, mixed $residence, mixed $cp, mixed $ville, mixed $pays)
    {
        $sql = "CALL creerServiceAccueil(:nomService,:residence, :voie,:cedex, :cp, :ville, :pays,:idEntreprise)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":nomService", $nomService);
        $stmt->bindValue(":idEntreprise", $idEntreprise);
        $stmt->bindValue(":voie", $voie);
        $stmt->bindValue(":residence", $residence);
        $stmt->bindValue(":cp", $cp);
        $stmt->bindValue(":ville", $ville);
        $stmt->bindValue(":pays", $pays);
        $stmt->bindValue(":cedex", "");
        $stmt->execute();
    }

    public function getFullByEntrepriseNom(int $identreprise, string $nomService): ?ServiceAccueil
    {
        $sql = "SELECT * FROM ServiceAccueil WHERE idEntreprise = :idEntreprise AND nomService = :nomService";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idEntreprise", $identreprise);
        $stmt->bindValue(":nomService", $nomService);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        return $this->construireDepuisTableau($result);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): ServiceAccueil
    {
        return new ServiceAccueil(
            $dataObjectFormatTableau['idservice'],
            $dataObjectFormatTableau['nomservice'],
            $dataObjectFormatTableau['adresse'],
            $dataObjectFormatTableau['adressecedex'],
            $dataObjectFormatTableau['adresseresidence'],
            $dataObjectFormatTableau['idville'],
            $dataObjectFormatTableau['identreprise']
        );
    }

    public function getCodePostal(int $idEntreprise, string $nomService): ?string
    {
        $idVille = $this->idVille($idEntreprise, $nomService);
        $sql = "SELECT codePostal FROM Ville WHERE idVille = :idVille";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idVille", $idVille);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        return $result['codepostal'];
    }

    public function idVille(int $idEntreprise, string $nomService): ?string
    {
        $sql = "SELECT idVille FROM ServiceAccueil WHERE idEntreprise = :idEntreprise AND nomService = :nomService";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idEntreprise", $idEntreprise);
        $stmt->bindValue(":nomService", $nomService);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        return $result['idville'];
    }

    public function getCommune(int $idEntreprise, string $nomService): ?string
    {
        $idVille = $this->idVille($idEntreprise, $nomService);
        $sql = "SELECT nomVille FROM Ville WHERE idVille = :idVille";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idVille", $idVille);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        return $result['nomville'];
    }

    public function getPays(int $idEntreprise, string $nomService): ?string
    {
        $idVille = $this->idVille($idEntreprise, $nomService);
        $sql = "SELECT pays FROM Ville WHERE idVille = :idVille";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->bindValue(":idVille", $idVille);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        return $result['pays'];
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