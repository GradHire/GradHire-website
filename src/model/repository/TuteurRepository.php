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

    public function full_name(): string
    {
        return $this->attributes["prenom"] . " " . $this->attributes["nom"];
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
            //throw new ServerErrorException();
        }
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Tuteur
    {
        return new Tuteur(
            $dataObjectFormatTableau["idUtilisateur"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["email"],
            $dataObjectFormatTableau["nom"],
            $dataObjectFormatTableau["numTelephone"],
            $dataObjectFormatTableau["hash"],
            $dataObjectFormatTableau["prenom"],
            $dataObjectFormatTableau["fonction"],
            $dataObjectFormatTableau["idEntreprise"]
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
            "hash",
            "prenom",
            "fonction",
            "idEntreprise"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "TuteurVue";
    }

}