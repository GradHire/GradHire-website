<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Candidature;

class CandidatureRepository extends AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    public function getById($id): ?Candidature
    {
        $sql = "SELECT * FROM Candidature WHERE idcandidature = :id";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $id]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) return null;
        return $this->construireDepuisTableau($resultat);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Candidature
    {
        return new Candidature(
            $dataObjectFormatTableau['idcandidature'],
            $dataObjectFormatTableau['datec'],
            $dataObjectFormatTableau['etatcandidature'],
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['idutilisateur']
        );
    }
    protected function getNomColonnes(): array
    {
        return [
            "idcandidature",
            "datecandidature",
            "etatcandidature",
            "idoffre",
            "idutilisateur"
        ];
    }
    public function getAll(): ?array
    {
        $sql= "SELECT * FROM Candidature ";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if ($resultat == false) return null;
        return $resultat;
    }

    protected function getNomTable(): string
    {
        return "Candidature";
    }
}