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
        return $this->attributes["prenomtuteurp"] . " " . $this->attributes["nomutilisateur"];
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Tuteur
    {
        return new Tuteur(
            $dataObjectFormatTableau["idutilisateur"],
            $dataObjectFormatTableau["bio"],
            $dataObjectFormatTableau["emailutilisateur"],
            $dataObjectFormatTableau["nomutilisateur"],
            $dataObjectFormatTableau["numtelutilisateur"],
            $dataObjectFormatTableau["hash"],
            $dataObjectFormatTableau["prenomtuteurp"],
            $dataObjectFormatTableau["fonctiontuteurp"],
            $dataObjectFormatTableau["identreprise"]
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Tuteur
    {
        try {
            $sql = "SELECT * FROM " . self::$view . " WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $idutilisateur]);
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

    protected function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "bio",
            "emailutilisateur",
            "nomutilisateur",
            "numtelutilisateur",
            "hash",
            "prenomtuteurp",
            "fonctiontuteurp",
            "identreprise"
        ];
    }

    protected function getNomTable(): string
    {
        return "TuteurVue";
    }

}