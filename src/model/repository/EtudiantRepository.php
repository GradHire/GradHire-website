<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Etudiant;
use app\src\model\dataObject\Roles;
use PDOException;

class EtudiantRepository extends LdapRepository
{
    protected static string $view = "EtudiantVue";
    protected static string $create_function = "creerEtu";
    protected static string $update_function = "updateEtudiant";

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
            $statement = Database::get_conn()->prepare("UPDATE `EtudiantVue` SET `annee`=? WHERE idUtilisateur=?");
            $statement->execute([$new_year, $this->id]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
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

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Etudiant
    {
        return new Etudiant(
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["email"],
            $dataObjectFormatTableau["nom"],
            $dataObjectFormatTableau["numtelephone"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["archiver"],
            $dataObjectFormatTableau["nomville"],
            $dataObjectFormatTableau["codepostal"],
            $dataObjectFormatTableau["pays"],
            $dataObjectFormatTableau["adresse"],
            $dataObjectFormatTableau["emailperso"],
            $dataObjectFormatTableau["numetudiant"],
            $dataObjectFormatTableau["codesexe"],
            $dataObjectFormatTableau["idgroupe"],
            $dataObjectFormatTableau["annee"],
            $dataObjectFormatTableau["datenaissance"],
            $dataObjectFormatTableau["loginldap"],
            $dataObjectFormatTableau["prenom"]
        );
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