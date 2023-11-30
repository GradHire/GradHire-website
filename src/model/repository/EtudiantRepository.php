<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\core\lib\StackTrace;
use app\src\model\Application;
use app\src\model\dataObject\Etudiant;
use app\src\model\dataObject\Roles;
use PDOException;

class EtudiantRepository extends LdapRepository
{
    protected static string $view = "EtudiantVue";
    protected static string $create_function = "creerEtu";
    protected static string $update_function = "updateEtudiant";

    /**
     * @throws ServerErrorException
     */
    public static function getNewsletterEmails($idOffre): array
    {
        try {
            $offre = OffresRepository::getInfosForNewsletter($idOffre);
            if (!$offre) return [];
            $estStage = !is_null($offre["est_stage"]);
            $estAlternance = !is_null($offre["est_alternance"]);
            $type = 'all';
            if ($estStage != $estAlternance)
                $type = $estStage ? "stage" : "alternance";
            $annee = $offre["anneevisee"] > 0 ? strval($offre["anneevisee"]) : "all";

            $sql = "SELECT e.email FROM Newsletter n
    JOIN EtudiantVue e ON n.idUtilisateur = e.idUtilisateur
    WHERE (n.annee = 'all' OR n.annee=?)
    AND (n.thematiques = '' OR n.thematiques LIKE ?)
    AND (n.offre_type = 'all' OR n.offre_type=?)";

            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([$annee, "%" . $offre["thematique"] . "%", $type]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if (!$resultat)
                return [];
            $emails = [];
            foreach ($resultat as $email) {
                $emails[] = $email["email"];
            }
            return $emails;
        } catch (PDOException $e) {

            StackTrace::print($e);
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getFullNameByID(int $id): string
    {
        try {
            $sql = "SELECT nom, prenom FROM etudiantvue where idutilisateur=?";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([$id]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return "";
            }
            return $resultat["prenom"] . " " . $resultat["nom"];
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function subscribeNewsletter($parameters): void
    {
        try {
            $sql = "
            INSERT INTO Newsletter (idUtilisateur, offre_type, annee, thematiques)
            VALUES (?, ?, ?, ?)
            ON CONFLICT (idUtilisateur) DO UPDATE
            SET offre_type = excluded.offre_type,
                annee = excluded.annee,
                thematiques = excluded.thematiques";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                Application::getUser()->id,
                $parameters["type"],
                $parameters["year"],
                implode(",", $parameters["theme"])
            ]);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }


    public function role(): Roles
    {
        return Roles::Student;
    }

    /**
     * @throws ServerErrorException
     */
    public function update_year(string $new_year): void
    {
        try {
            $statement = Database::get_conn()->prepare("UPDATE etudiant SET annee=? WHERE idUtilisateur=?");
            $statement->execute([$new_year, $this->id]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getByNumEtudiant($numEtudiant): ?Etudiant
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE numEtudiant = :numEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['numEtudiant' => $numEtudiant]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Etudiant
    {
        return new Etudiant(
            $dataObjectFormatTableau
        );
    }

    public function getByNumEtudiantFull($numEtudiant): ?Etudiant
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE numEtudiant = :numEtudiant";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['numEtudiant' => $numEtudiant]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    public function updateEtu(string $numEtu, string $nom, string $prenom, string $tel, string $mailPerso, string $mailUniv, string $adresse, string $codePostal, string $ville, string $pays, ?string $groupe): void
    {
        $statement = Database::get_conn()->prepare("Call updateetuimp(?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute([$numEtu, $nom, $prenom, $tel, $mailPerso, $mailUniv, null, $adresse, $codePostal, $pays, $ville, $groupe]);

    }

    /**
     * @throws ServerErrorException
     */
    public
    function getByIdFull($idutilisateur): ?Etudiant
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
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "email",
            "nom",
            "numTelephone",
            "bio",
            "archiver",
            "nomVille",
            "codePostal",
            "pays",
            "adresse",
            "emailPerso",
            "numEtudiant",
            "codeSexe",
            "idGroupe",
            "annee",
            "dateNaissance",
            "loginLdap",
            "prenom"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "EtudiantVue";
    }
}