<?php
namespace app\src\model\repository;
use app\src\core\db\Database;
use app\src\model\repository\AbstractRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Etudiant;
class EtudiantRepository extends UtilisateurRepository{

    private static string $view = "EtudiantVue";
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

    public function getByIdFull($idutilisateur): ?Etudiant
    {
        $sql = "SELECT * FROM ".self::$view." WHERE idutilisateur = :idutilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idutilisateur' => $idutilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
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