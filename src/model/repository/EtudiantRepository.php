<?php
namespace app\src\model\repository;
use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\repository\AbstractRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Etudiant;
use app\src\model\dataObject\Roles;
use PDOException;

class EtudiantRepository extends LdapRepository {

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
            $statement = Database::get_conn()->prepare("UPDATE `EtudiantVue` SET `annee`=? WHERE idutilisateur=?");
            $statement->execute([$new_year, $this->id]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }
    protected function construireDepuisTableau(array $dataObjectFormatTableau): Etudiant
    {
        return new Etudiant(
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["emailutilisateur"],
            $dataObjectFormatTableau["nomutilisateur"],
            $dataObjectFormatTableau["numtelutilisateur"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["mailperso"],
            $dataObjectFormatTableau["codesexeetudiant"],
            $dataObjectFormatTableau["numetudiant"],
            $dataObjectFormatTableau["datenaissance"],
            $dataObjectFormatTableau["idgroupe"],
            $dataObjectFormatTableau["annee"],
            $dataObjectFormatTableau["prenomutilisateurldap"],
            $dataObjectFormatTableau["loginldap"]
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Etudiant
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $idutilisateur]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat){
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "bio",
            "emailutilisateur",
            "nomutilisateur",
            "numtelutilisateur",
            "mailperso",
            "codesexeetudiant",
            "numetudiant",
            "datenaissance",
            "idgroupe",
            "annee",
            "prenomutilisateurldap",
            "loginldap"
        ];
    }

    protected function getNomTable(): string
    {
        return "EtudiantVue";
    }
}