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

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Candidature
    {
        return new Candidature(
            $dataObjectFormatTableau['idcandidature'],
            $dataObjectFormatTableau['datec'],
            $dataObjectFormatTableau['etatcandidature'],
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['idUtilisateur']
        );
    }

    public function getByIdEntreprise($identreprise, string $etat): ?array
    {
        $sql = "SELECT idcandidature,datec,etatcandidature,$this->nomTable.idoffre,$this->nomTable.idUtilisateur FROM $this->nomTable JOIN Offre ON Offre.idoffre=$this->nomTable.idoffre WHERE Offre.idUtilisateur= :id AND etatcandidature=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise, 'etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;

    }

    public function getByIdEtudiant($idEtudiant, string $etat): ?array
    {
        $sql = "SELECT idcandidature,datec,etatcandidature,$this->nomTable.idoffre,$this->nomTable.idUtilisateur FROM $this->nomTable  WHERE idUtilisateur= :id AND etatcandidature=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $idEtudiant, 'etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;

    }

    public function getByStatement(string $etat)
    {
        $sql = "SELECT idcandidature,datec,etatcandidature,$this->nomTable.idoffre,$this->nomTable.idUtilisateur FROM $this->nomTable JOIN Offre ON Offre.idoffre=$this->nomTable.idoffre WHERE etatcandidature=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    public function setEtatCandidature(int $idCandidature, string $etat)
    {
        $sql = "UPDATE $this->nomTable SET etatcandidature=:etat WHERE idcandidature=:id;";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $idCandidature, 'etat' => $etat]);

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
            //throw new ServerErrorException();
        }
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "idcandidature",
            "datecandidature",
            "etatcandidature",
            "idoffre",
            "idUtilisateur"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "Candidature";
    }
}