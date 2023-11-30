<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Postuler;
use PDOException;

class PostulerRepository extends AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    private string $nomTable = "PostulerVue";

    /**
     * @throws ServerErrorException
     */
    public static function postuler($id): void
    {
        try {
            $stmt = Database::get_conn()->prepare("INSERT INTO Postuler(idoffre, idUtilisateur, dates) VALUES (?,?,?)");
            $values = [
                $id,
                Application::getUser()->id(),
                date("Y-m-d H:i:s")
            ];
            $stmt->execute($values);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getById($idOffre, $idUtilisateur): ?Postuler
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idOffre=:idOffre AND idUtilisateur=:idUtilisateur";
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
            $dataObjectFormatTableau['identreprise'],
            $dataObjectFormatTableau['statut']
        );
    }

    public function getByIdEntreprise($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE identreprise= :id AND statut::text='validee' OR statut::text='refusee'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        return $requete->fetchAll();
    }

    public function getCandidaturesAttenteEntreprise($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE identreprise= :id AND statut::text LIKE 'en attente%'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        return $requete->fetchAll();
    }

    public function getCandidaturesAttenteEtudiant($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idUtilisateur= :id AND statut::text LIKE 'en attente%'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $identreprise]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        return $requete->fetchAll();
    }

    public function getByIdEtudiant($idEtudiant): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable  WHERE idUtilisateur= :id AND statut::text='validee' OR statut::text='refusee'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['id' => $idEtudiant]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        return $requete->fetchAll();
    }

    /**
     * @throws ServerErrorException
     */
    public function setStatutPostuler(int $idutilisateur, int $idoffre, string $etat): void
    {
        $sql = "UPDATE $this->nomTable SET statut=:etat WHERE idUtilisateur=:idutilisateur AND idOffre=:idoffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['etat' => $etat, 'idutilisateur' => $idutilisateur, 'idoffre' => $idoffre]);
    }

    public function getByStatementValideeOrRefusee(): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut::text='validee' OR statut::text='refusee'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        return $requete->fetchAll();
    }

    public function getByStatementAttente(): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE statut::text LIKE 'en attente%'";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        return $requete->fetchAll();
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
            throw new ServerErrorException('erreurs getAll');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getStatsCandidaturesParMois(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM candidatures_par_mois_cache;")->fetchAll();
    }

    public function getIfSuivi(int $idUtilisateur, $idetu, $idoffre): bool
    {
        $statement = Database::get_conn()->prepare("SELECT idUtilisateur FROM Supervise WHERE idutilisateur = :idutilisateur AND idetudiant = :idetudiant AND idoffre = :idoffre");
        $statement->bindParam(":idutilisateur", $idUtilisateur);
        $statement->bindParam(":idetudiant", $idetu);
        $statement->bindParam(":idoffre", $idoffre);
        $statement->execute();
        $data = $statement->fetch();
        if ($data == null) return false;
        return true;
    }

    public function getByIdOffre(mixed $idOffre): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE idOffre=:idOffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idOffre' => $idOffre]);
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
    public function getSiTuteurPostuler(?int $getIdUtilisateur, ?int $getIdOffre)
    {
        try {
            $sql = "SELECT idUtilisateur FROM Supervise WHERE idEtudiant=:idUtilisateur AND idOffre=:idOffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $getIdUtilisateur, 'idOffre' => $getIdOffre]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) return false;
            return true;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getSiTuteurPostuler');
        }
    }

    public function getTuteurByIdOffre(mixed $idOffre): ?array
    {
        try {
            $sql = "SELECT * FROM Supervise WHERE idOffre=:idOffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idOffre' => $idOffre]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if (!$resultat) return null;
            return $resultat;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getTuteurByIdOffre');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function refuserCandidature(int $idutilisateur, mixed $idOffre): void
    {
        try {
            $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idutilisateur
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs refuserCandidature');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerCandidatureEtudiant(mixed $idEtudiant, mixed $idOffre): void
    {
        try {
            $sql = "UPDATE Postuler SET statut = 'en attente tuteur prof' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idEtudiant
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs validerCandidatureEtudiant');
        }
        try {
            $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idOffre!=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idEtudiant
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs validerCandidatureEtudiant');
        }
        try {
            $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur!=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idEtudiant
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs validerCandidatureEtudiant');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerCandidatureEntreprise(int $idUtilisateur, int $idOffre): void
    {
        try {
            $sql = "UPDATE Postuler SET statut = 'en attente etudiant' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre,
                'idutilisateur' => $idUtilisateur,
            ]);
        } catch (\Exception) {
            throw new ServerErrorException('erreurs validerCandidatureEntreprise');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getByStatementAttenteTuteur(): array
    {
        try {
            $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM $this->nomTable WHERE CAST(statut as TEXT) = 'en attente tuteur prof' OR CAST(statut as TEXT) = 'en attente responsable' OR CAST(statut as TEXT) = 'en attente tuteur entreprise'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            return $requete->fetchAll();
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getByStatementAttenteTuteur');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getByStatementTuteur(int $idutilisateur, string $string)
    {
        if ($string == 'validee') {
            try {
                $sql = "SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur, su.idUtilisateur as idTuteur,idEntreprise,p.statut FROM $this->nomTable p JOIN Supervise su ON su.idOffre=p.idOffre WHERE su.statut::text = 'validee' AND su.idutilisateur=:idutilisateur AND CAST(p.statut AS TEXT) = CAST(su.statut AS TEXT)";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute([
                    'idutilisateur' => $idutilisateur
                ]);
                $requete->setFetchMode(\PDO::FETCH_ASSOC);
                return $requete->fetchAll();
            } catch (\Exception) {
                throw new ServerErrorException('erreurs getByStatementTuteurValidee');
            }
        } else if ($string == 'refusee') {
            try {
                $sql = "SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur, su.idUtilisateur as idTuteur,idEntreprise,p.statut FROM $this->nomTable p JOIN Supervise su ON su.idOffre=p.idOffre WHERE su.idUtilisateur = :idutilisateur AND CAST(p.statut AS TEXT) != CAST(su.statut AS TEXT) AND su.statut::text = 'refusee'";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute([
                    'idutilisateur' => $idutilisateur
                ]);
                $resultatRequete = $requete->fetchAll();
                foreach ($resultatRequete as $item) {
                    $item['statut'] = 'refusee';
                }
                return $resultatRequete;
            } catch (\Exception) {
                throw new ServerErrorException('erreurs getByStatementTuteurRefusee');
            }
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfStudentAlreadyAccepted(int $idOffre)
    {
        try {
            $sql = "SELECT COUNT(idUtilisateur) as nbAccepter FROM $this->nomTable WHERE idOffre=:idoffre AND (statut::text = 'validee' OR statut::text = 'en attente tuteur' OR statut::text = 'en attente responsable')";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idoffre' => $idOffre
            ]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat["nbaccepter"] == 1) return true;
            return false;
        } catch (\Exception) {
            throw new ServerErrorException('erreurs getIfStudentAlreadyAccepted');
        }
    }

    public function getIfValideeInArray(?array $candidatures): bool
    {
        foreach ($candidatures as $candidature) {
            if ($candidature['statut'] == 'validee') return true;
        }
        return false;
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