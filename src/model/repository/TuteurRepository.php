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
    public function addTuteur($idUtilisateur, $idOffre, $idEtudiant, $fonction, $idEntreprise): void
    {
        try {
            $sql = "INSERT INTO TuteurProf VALUES(:idUtilisateur,:idOffre,:idEtudiant,:fonction,:idEntreprise)";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute([
                'idUtilisateur' => $idUtilisateur,
                'idOffre' => $idOffre,
                'idEtudiant' => $idEtudiant,
                'fonction' => $fonction,
                'idEntreprise' => $idEntreprise
            ]);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfTuteurAlreadyExist($idUtilisateur, $idOffre, $idEtudiant): bool
    {
        $sql = "SELECT * FROM TuteurProf WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant
        ]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == null) {
            return false;
        }
        return true;
    }

    public function removeTuteur(int $getIdutilisateur, mixed $idOffre, $idEtudiant, string $string, int $identreprise)
    {
        $sql = "DELETE FROM TuteurProf WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND fonction = :fonction AND idEntreprise = :idEntreprise";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute([
            'idUtilisateur' => $getIdutilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant,
            'fonction' => $string,
            'idEntreprise' => $identreprise
        ]);
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