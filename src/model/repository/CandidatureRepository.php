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
    private string $nomTable = "Candidature";
    public function getById($id): ?Candidature
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idcandidature = :id;";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $id]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) return null;
        return $this->construireDepuisTableau($resultat);
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdEntreprise($identreprise): ?array
    {
        $sql = "SELECT idcandidature,datec,etatcandidature,$this->nomTable.idoffre,$this->nomTable.idutilisateur FROM $this->nomTable JOIN Offre ON Offre.idoffre=$this->nomTable.idoffre WHERE Offre.idutilisateur= :id";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        foreach($requete as $item){
            $resultat[]=$this->construireDepuisTableau($item);
        }
        return $resultat;
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

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM Candidature ";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if ($resultat == false) return null;
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected function getNomTable(): string
    {
        return "Candidature";
    }
}