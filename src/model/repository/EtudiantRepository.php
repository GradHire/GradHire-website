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
            //throw new ServerErrorException();
        }
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
            //throw new ServerErrorException();
        }
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Etudiant
    {
        return new Etudiant(
            $dataObjectFormatTableau["idUtilisateur"],
            $dataObjectFormatTableau["email"],
            $dataObjectFormatTableau["nom"],
            $dataObjectFormatTableau["numTelephone"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["mailPerso"],
            $dataObjectFormatTableau["codeSexe"],
            $dataObjectFormatTableau["numEtudiant"],
            $dataObjectFormatTableau["datenaissance"],
            $dataObjectFormatTableau["idgroupe"],
            $dataObjectFormatTableau["annee"],
            $dataObjectFormatTableau["prenomLdap"],
            $dataObjectFormatTableau["loginLdap"]
        );
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "bio",
            "email",
            "nom",
            "numTelephone",
            "mailPerso",
            "codeSexe",
            "numEtudiant",
            "datenaissance",
            "idgroupe",
            "annee",
            "prenomLdap",
            "loginLdap"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "EtudiantVue";
    }
}