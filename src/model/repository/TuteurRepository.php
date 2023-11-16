<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Tuteur;
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
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
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
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["email"],
            $dataObjectFormatTableau["nom"],
            $dataObjectFormatTableau["numtelephone"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["archiver"],
            $dataObjectFormatTableau["prenom"],
            $dataObjectFormatTableau["fonction"],
            $dataObjectFormatTableau["identreprise"],
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function addTuteur($idUtilisateur, $idOffre, $idEtudiant): void
    {
        try {
            $sql = "UPDATE Supervise SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant,
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur Update Supervise');
        }
        try {
            $sql = "UPDATE Staff SET role = 'tuteur' WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur Insert Staff');
        }
        try {
            $sql = "UPDATE Postuler SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idEtudiant,
                'idOffre' => $idOffre,
            ]);
        } catch (PDOException) {
            throw new ServerErrorException('erreur Update Postuler');
        }
        $this->refuserTuteur($idUtilisateur, $idOffre, $idEtudiant);
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
    public function getIfTuteurAlreadyExist($idUtilisateur, $idOffre, $idEtudiant): bool
    {
        try {
            $sql = "SELECT * FROM Supervise s JOIN Staff st ON st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND role = 'tuteur' AND statut = 'validee'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant
            ]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
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
    public function refuserTuteur(int $getIdutilisateur, mixed $idOffre, $idEtudiant)
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
    public function annulerTuteur(int $iduser, int $idOffre, int $idetudiant): void
    {
        try {
            //on vas d'abord verifier si il est plusieurs fois tutor
            $sql = "SELECT COUNT(*) as 'nbFoisTuteur' FROM Supervise s JOIN Staff st on st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND statut = 'validee'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $iduser,
            ]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat["nbfoistuteur"] < 1) {
                $sql = "UPDATE Staff SET role = 'enseignant' WHERE idUtilisateur = :idUtilisateur";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute([
                    'idUtilisateur' => $iduser,
                ]);
            }
        } catch (PDOException) {
            throw new ServerErrorException('erreur Insert Staff');
        }
        try{
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
    public function seProposer(int $idutilisateur, int $idOffre, int $idetudiant): void {
        try {
            $statement = Database::get_conn()->prepare("INSERT INTO Supervise VALUES (?,?,?,'en attente');");
            $statement->bindParam(1, $idutilisateur);
            $statement->bindParam(2, $idOffre);
            $statement->bindParam(3, $idetudiant);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur  lors du se proposer de la convention");
        }
        try {
            $statement = Database::get_conn()->prepare("UPDATE Postuler SET Statut = 'en attente responsable' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre");
            $statement->bindParam(":idUtilisateur", $idetudiant);
            $statement->bindParam(":idOffre", $idOffre);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors du se proposer de la convention");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function seDeProposer(int $idUtilisateur, mixed $idOffre, $idEtudiant)
    {
        try {
            $statement = Database::get_conn()->prepare("DELETE FROM Supervise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant");
            $statement->bindParam(":idUtilisateur", $idUtilisateur);
            $statement->bindParam(":idOffre", $idOffre);
            $statement->bindParam(":idEtudiant", $idEtudiant);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors du se de proposer de la convention");
        }
        try {
            $statement = Database::get_conn()->prepare("UPDATE Postuler SET Statut = 'en attente tuteur' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre");
            $statement->bindParam(":idUtilisateur", $idEtudiant);
            $statement->bindParam(":idOffre", $idOffre);
            $statement->execute();
        } catch (\Exception $e) {
            throw new ServerErrorException("Erreur lors du se de proposer de la convention");
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