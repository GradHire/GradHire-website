<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Tuteur;
use Exception;
use PDO;
use PDOException;

class TuteurRepository extends ProRepository
{
    protected static string $view = "TuteurVue";
    protected static string $update_function = "updateTuteur";




    public function role(): Roles
    {
        return Roles::Tutor;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Tuteur
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $idutilisateur]);
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Tuteur
    {
        return new Tuteur(
            $dataObjectFormatTableau
        );
    }

    /**
     * @throws ServerErrorException
     */
    public static function addTuteur($idUtilisateur, $idOffre, $idEtudiant): void
    {
        try {
            $sql = "UPDATE Supervise SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant,
            ]);
            $sql = "UPDATE Staff SET role = 'tuteurprof' WHERE idUtilisateur = :idUtilisateur AND role = 'enseignant'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
            ]);
            $sql = "UPDATE Postuler SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idEtudiant,
                'idOffre' => $idOffre,
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur Update Postuler');
        }
        self::refuserTuteur($idUtilisateur, $idOffre, $idEtudiant);
        try {
            //refuser toutes les autres candidatures
            $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idUtilisateur != :idUtilisateur AND idOffre = :idOffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idEtudiant,
                'idOffre' => $idOffre,
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur Update Postuler');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTuteurWhereIsNotMyId($idUtilisateur, $idOffre, $idEtudiant): ?array
    {
        try {
            $sql = "SELECT * FROM Supervise WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre AND s.idUtilisateur != :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant
            ]);
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException('erreur getTuteurWhereIsNotMyId');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function refuserTuteur(int $getIdutilisateur, mixed $idOffre, $idEtudiant): void
    {
        try {
            $sql = "UPDATE Supervise SET Statut = 'refusee' WHERE idUtilisateur!=:idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $getIdutilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur refuser tuteur');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfTuteurAlreadyExist($idUtilisateur, $idOffre, $idEtudiant): bool
    {
        try {
            $sql = "SELECT * FROM Supervise s JOIN Staff st ON st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND role = 'tuteurprof' AND statut = 'validee'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant
            ]);
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat == null || $resultat["statut"] == "en attente" || $resultat["statut"] == "refusee") {
                return false;
            }
            return true;
        } catch (PDOException) {
            throw new ServerErrorException('erreur getIfTuteurAlreadyExist');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function annulerTuteur(int $iduser, int $idOffre, int $idetudiant): void
    {
        try {
            //on vas d'abord verifier si il est plusieurs fois tutor
            $sql = "SELECT COUNT(*) as \"nbFoisTuteur\" FROM Supervise s JOIN Staff st on st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND statut = 'validee'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $iduser,
            ]);
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat["nbFoisTuteur"] < 1) {
                $sql = "UPDATE Staff SET role = 'enseignant' WHERE idUtilisateur = :idUtilisateur";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute([
                    'idUtilisateur' => $iduser,
                ]);
            }
        } catch (PDOException) {
            throw new ServerErrorException('erreur Insert Staff');
        }
        try {
            $sql = "UPDATE Supervise SET Statut = 'en attente' WHERE idOffre = :idOffre AND idEtudiant = :idEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idOffre' => $idOffre,
                'idEtudiant' => $idetudiant
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur annuler tuteur');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function seProposerProf(int $idutilisateur, int $idOffre, int $idetudiant): void
    {
        try {
            $statement = Database::get_conn()->prepare("INSERT INTO Supervise VALUES (?,?,?,'en attente',null);");
            $statement->bindParam(1, $idutilisateur);
            $statement->bindParam(2, $idOffre);
            $statement->bindParam(3, $idetudiant);
            $statement->execute();
        } catch (Exception) {
            throw new ServerErrorException("Erreur  lors du se proposer de la convention");
        }
        try {
            $statement = Database::get_conn()->prepare("UPDATE Postuler SET Statut = 'en attente tuteur entreprise' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre");
            $statement->bindParam(":idUtilisateur", $idetudiant);
            $statement->bindParam(":idOffre", $idOffre);
            $statement->execute();
        } catch (Exception) {
            throw new ServerErrorException("Erreur lors du se proposer de la convention");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function seDeProposerProf(int $idUtilisateur, mixed $idOffre, $idEtudiant): void
    {
        try {
            $statement = Database::get_conn()->prepare("DELETE FROM Supervise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant");
            $statement->bindParam(":idUtilisateur", $idUtilisateur);
            $statement->bindParam(":idOffre", $idOffre);
            $statement->bindParam(":idEtudiant", $idEtudiant);
            $statement->execute();
        } catch (Exception ) {
            throw new ServerErrorException("Erreur lors du se de proposer de la convention");
        }
        try {
            $statement = Database::get_conn()->prepare("UPDATE Postuler SET Statut = 'en attente tuteur entreprise' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre");
            $statement->bindParam(":idUtilisateur", $idEtudiant);
            $statement->bindParam(":idOffre", $idOffre);
            $statement->execute();
        } catch (Exception) {
            throw new ServerErrorException("Erreur lors du se de proposer de la convention");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getNomTuteurByIdEtudiantAndIdOffre(int $idetudiant, int $idoffre): ?string
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT nom, prenom FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre");
            $statement->bindParam(":idEtudiant", $idetudiant);
            $statement->bindParam(":idOffre", $idoffre);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $statement->fetch();
            return $resultat["prenom"] . " " . $resultat["nom"];
        } catch (Exception) {
            throw new ServerErrorException("Erreur getNomTuteurByIdEtudiantAndIdOffre");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getTuteurByIdEtudiantAndIdOffre(int $idetudiant, int $idoffre): ?array
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre");
            $statement->bindParam(":idEtudiant", $idetudiant);
            $statement->bindParam(":idOffre", $idoffre);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $statement->fetch();
            if (!$resultat) {
                return null;
            }
            return $resultat;
        } catch (Exception) {
            throw new ServerErrorException("Erreur getNomTuteurByIdEtudiantAndIdOffre");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function assigneCommeTuteurEntreprise(mixed $idUtilisateur, mixed $idOffre, mixed $idEtudiant, mixed $idTuteurEntreprise): void
    {
        try {
            $sql = "UPDATE Supervise SET idTuteurEntreprise = :idTuteurEntreprise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant,
                'idTuteurEntreprise' => $idTuteurEntreprise
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur assigneCommeTuteurEntreprise');
        }
        try {
            $sql = "UPDATE Postuler SET statut = 'en attente responsable' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idEtudiant,
                'idOffre' => $idOffre,
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur assigneCommeTuteurEntreprise');
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTuteurEnAttente(int $getIdutilisateur, mixed $idOffre, mixed $idEtudiant)
    {
        try {
            $sql = "SELECT * FROM Supervise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND statut = 'en attente'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $getIdutilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant
            ]);
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException('erreur getTuteurEnAttente');
        }
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "email",
            "nom",
            "numtelephone",
            "bio",
            "archiver",
            "prenom",
            "fonction",
            "identreprise"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "TuteurVue";
    }
}