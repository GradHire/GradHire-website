<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Convention;

class ConventionRepository extends AbstractRepository
{
    protected static string $table = "Convention";

    public static function createSoutenance(?array $array)
    {
        try {
            $sql = "INSERT INTO Soutenance (numConvention, idtuteurprof, idtuteurentreprise, idprof, debut_soutenance, fin_soutenance) VALUES (:numConvention, :dateSoutenance, :heureSoutenance, :salleSoutenance, :idUtilisateur)";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'numConvention' => $array['numConvention'],
                'dateSoutenance' => $array['dateSoutenance'],
                'heureSoutenance' => $array['heureSoutenance'],
                'salleSoutenance' => $array['salleSoutenance'],
                'idUtilisateur' => $array['idUtilisateur']
            ]);
        } catch (\Exception $e) {
            StackTrace::print( $e);
        }
    }

    public static function getByNumConvention(mixed $numConvention)
    {
        try {
        $sql = "SELECT * FROM \"conventionValideVue\" WHERE numconvention = :numConvention";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['numConvention' => $numConvention]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) {
            return null;
        }
        return $resultat;
        } catch (\Exception $e) {
            StackTrace::print( $e);
        }
    }


    /**
     * @throws ServerErrorException
     */
    public function getAll(): array
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table);
            $statement->execute();
            $data = $statement->fetchAll();
            $conventions = [];
            foreach ($data as $row) {
                $conventions[] = $this->construireDepuisTableau($row);
            }
            return $conventions;
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération des conventions", 500, $e);
        }
    }

    public function getByIdOffreAndIdUser(int $idOffre, int $idUser): ?Convention
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table . " WHERE idoffre = :idOffre AND idutilisateur = :idUser");
            $statement->bindParam(":idOffre", $idOffre);
            $statement->bindParam(":idUser", $idUser);
            $statement->execute();
            $data = $statement->fetch();
            if (!$data) return null;
            return $this->construireDepuisTableau($data);
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getById(int $id): ?Convention
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$table . " WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $data = $statement->fetch();
            if (!$data) return null;
            return $this->construireDepuisTableau($data);
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerPedagogiquement(int $id): void
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 1 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $mail = new MailRepository();
            $offre = (new OffresRepository())->getById($this->getById($id)->getIdOffre());
            $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
            $mail->send_Mail(
                [$entreprise->getEmailutilisateur()],
                "Convention validée pédagogiquement",
                "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " a été validée pédagogiquement par le Staff "
            );
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la validation pédagogique de la convention", 500, $e);
        }
    }

    public function construireDepuisTableau(array $dataObjectFormatTableau): Convention
    {
        return new Convention(
            $dataObjectFormatTableau["numconvention"],
            $dataObjectFormatTableau["origineconvention"],
            $dataObjectFormatTableau["conventionvalidee"],
            $dataObjectFormatTableau["conventionvalideepedagogiquement"],
            $dataObjectFormatTableau["datemodification"],
            $dataObjectFormatTableau["datecreation"],
            $dataObjectFormatTableau["idsignataire"],
            $dataObjectFormatTableau["idinterruption"],
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["idoffre"],
            $dataObjectFormatTableau["commentaire"]
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function checkIfConventionsValideePedagogiquement(int $idConvention): bool
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT conventionvalideepedagogiquement FROM " . static::$table . " WHERE numconvention = :idConvention");
            $statement->bindParam(":idConvention", $idConvention);
            $statement->execute();
            $data = $statement->fetch();
            if (!$data) return false;
            return (bool)$data["conventionvalideepedagogiquement"];
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la récupération de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidatePedagogiquement(mixed $id): void
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 0 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $mail = new MailRepository();
            $offre = (new OffresRepository())->getById($this->getById($id)->getIdOffre());
            $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
            $mail->send_Mail(
                [$entreprise->getEmailutilisateur()],
                "Convention non validée pédagogiquement",
                "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " n'a pas été validée pédagogiquement par le Staff "
            );
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de l'archivage pédagogique de la convention", 500, $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidate(mixed $id)
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalidee = 0 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $mail = new MailRepository();
            $offre = (new OffresRepository())->getById($this->getById($id)->getIdOffre());
            $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
            $etudiant = (new EtudiantRepository([]))->getByIdFull($this->getById($id)->getIdUtilisateur());
            $mail->send_Mail(
                [$etudiant->getEmailutilisateur()],
                "Convention non validée par l'enreprise " . $entreprise->getNomutilisateur(),
                "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " n'a pas été validée par l'entreprise " . $entreprise->getNomutilisateur()
            );
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de l'archivage de la convention", 500, $e);
        }
    }


    /**
     * @throws ServerErrorException
     */
    public function valider(mixed $id)
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE " . static::$table . " SET conventionvalidee = 1 WHERE numconvention = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();
            $mail = new MailRepository();
            $offre = (new OffresRepository())->getById($this->getById($id)->getIdOffre());
            $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
            $etudiant = (new EtudiantRepository([]))->getByIdFull($this->getById($id)->getIdUtilisateur());
            $mail->send_Mail(
                [$etudiant->getEmailutilisateur()],
                "Convention validée par l'enreprise " . $entreprise->getNomutilisateur(),
                "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " a été validée par l'entreprise " . $entreprise->getNomutilisateur()
            );
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors de la validation de la convention", 500, $e);
        }
    }

    public function getPourcentageEtudiantsConventionCetteAnnee(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM pourcentage_etudiants_convention_cette_annee_cache;")->fetch();
    }


    protected function getNomTable(): string
    {
        return static::$table;
    }

    protected function getNomColonnes(): array
    {
        return [
            "numconvention",
            "origineconvention",
            "conventionvalidee",
            "conventionvalideepedagogiquement",
            "datemodification",
            "datecreation",
            "idsignataire",
            "idinterruption",
            "idutilisateur",
            "idoffre",
            "commentaire"
        ];
    }
}