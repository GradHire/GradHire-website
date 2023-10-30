<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Staff;
use app\src\model\dataObject\Tuteur;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Utilisateur;
use app\src\model\repository\ProRepository;
use app\src\model\dataObject\Roles;
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
        return $this->attributes["prenom"] . " " . $this->attributes["nomUtilisateur"];
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
            $dataObjectFormatTableau["emailUtilisateur"],
            $dataObjectFormatTableau["nomUtilisateur"],
            $dataObjectFormatTableau["numTelUtilisateur"],
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
            "emailUtilisateur",
            "nomUtilisateur",
            "numTelUtilisateur",
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