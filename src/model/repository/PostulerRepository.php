<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Postuler;

class PostulerRepository extends AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    private string $nomTable = "PostulerVue";
    /**
     * @throws ServerErrorException
     */
    public function getById($idOffre, $idUtilisateur): ?Postuler
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idOffre=:idOffre AND idUtilisateur=:idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idOffre' => $idOffre, 'idUtilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) return null;
        return $this->construireDepuisTableau($resultat);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Postuler
    {
        return new Postuler(
            $dataObjectFormatTableau['sujet'],
            $dataObjectFormatTableau['nom'],
            $dataObjectFormatTableau['dates'],
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['statut']
        );
    }

    public function getByIdEntreprise($identreprise, string $etat): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,statut FROM $this->nomTable WHERE idUtilisateur= :id AND statut=:etat";
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
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,statut FROM $this->nomTable  WHERE idUtilisateur= :id AND statut=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $idEtudiant, 'etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;

    }

    public function setStatutPostuler(int $idutilisateur, int $idoffre, string $etat): void
    {
        $sql = "UPDATE $this->nomTable SET statut=:etat WHERE idUtilisateur=:idutilisateur AND idOffre=:idoffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['etat' => $etat, 'idutilisateur' => $idutilisateur, 'idoffre' => $idoffre]);
    }

    public function getByStatement(string $etat): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,statut FROM $this->nomTable WHERE statut=:etat";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['etat' => $etat]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM $this->nomTable;";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if (!$resultat) return null;
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfPostuler(int $idConvention, int $idUtilisateur): bool
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM SuivreConvention WHERE idutilisateur = :idutilisateur AND idconvention = :idconvention");
            $statement->bindParam(":idutilisateur", $idUtilisateur);
            $statement->bindParam(":idconvention", $idConvention);
            $statement->execute();
            $data = $statement->fetch();
            if (!$data) return false;
            return true;
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
        }
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "sujet",
            "nom",
            "dates",
            "idOffre",
            "idUtilisateur",
            "statut"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "PostulerVue";
    }
}