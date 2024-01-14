<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Tuteur;

class TuteurRepository extends ProRepository
{
    protected static string $view = "TuteurVue";
    protected static string $update_function = "updateTuteur";

    /**
     * @throws ServerErrorException
     */
    public static function addTuteur($idUtilisateur, $idOffre, $idEtudiant): void
    {
        $sql = "UPDATE Supervise SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
        self::Execute($sql, [
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant,
        ]);
        $sql = "UPDATE Staff SET role = 'tuteurprof' WHERE idUtilisateur = :idUtilisateur AND role = 'enseignant'";
        self::Execute($sql, [
            'idUtilisateur' => $idUtilisateur,
        ]);
        $sql = "UPDATE Postuler SET Statut = 'validee' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre";
        self::Execute($sql, [
            'idUtilisateur' => $idEtudiant,
            'idOffre' => $idOffre,
        ]);
        self::refuserTuteur($idUtilisateur, $idOffre, $idEtudiant);
        $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idUtilisateur != :idUtilisateur AND idOffre = :idOffre";
        self::Execute($sql, [
            'idUtilisateur' => $idEtudiant,
            'idOffre' => $idOffre,
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function refuserTuteur(int $getIdutilisateur, mixed $idOffre, $idEtudiant): void
    {
        $sql = "UPDATE Supervise SET Statut = 'refusee' WHERE idUtilisateur!=:idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
        self::Execute($sql, [
            'idUtilisateur' => $getIdutilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTuteurWhereIsNotMyId($idUtilisateur, $idOffre, $idEtudiant): ?array
    {
        $sql = "SELECT * FROM Supervise WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre AND s.idUtilisateur != :idUtilisateur";
        return self::FetchAssoc($sql, [
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function annulerTuteur(int $iduser, int $idOffre, int $idetudiant): void
    {
        $sql = "SELECT COUNT(*) as \"nbFoisTuteur\" FROM Supervise s JOIN Staff st on st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND statut = 'validee'";
        $resultat = self::FetchAssoc($sql, [
            'idUtilisateur' => $iduser,
        ]);
        if ($resultat["nbFoisTuteur"] < 1)
            self::Execute($sql, [
                'idUtilisateur' => $iduser,
            ]);
        self::Execute($sql, [
            'idOffre' => $idOffre,
            'idEtudiant' => $idetudiant
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function seProposerProf(int $idutilisateur, int $idOffre, int $idetudiant): void
    {
        self::Execute("INSERT INTO Supervise VALUES (?,?,?,'en attente',null);", [
            $idutilisateur,
            $idOffre,
            $idetudiant
        ]);
        self::Execute("UPDATE Postuler SET Statut = 'en attente tuteur entreprise' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre", [
            'idUtilisateur' => $idetudiant,
            'idOffre' => $idOffre
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function seDeProposerProf(int $idUtilisateur, mixed $idOffre, $idEtudiant): void
    {
        self::Execute("DELETE FROM Supervise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant", [
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant
        ]);
        self::Execute("UPDATE Postuler SET Statut = 'en attente tuteur entreprise' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre", [
            'idUtilisateur' => $idEtudiant,
            'idOffre' => $idOffre
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function assigneCommeTuteurEntreprise(mixed $idUtilisateur, mixed $idOffre, mixed $idEtudiant, mixed $idTuteurEntreprise): void
    {
        $sql = "UPDATE Supervise SET idTuteurEntreprise = :idTuteurEntreprise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant";
        self::Execute($sql, [
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant,
            'idTuteurEntreprise' => $idTuteurEntreprise
        ]);
        $sql = "UPDATE Postuler SET statut = 'en attente responsable' WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre";
        self::Execute($sql, [
            'idUtilisateur' => $idEtudiant,
            'idOffre' => $idOffre,
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTuteurEnAttente(int $getIdutilisateur, mixed $idOffre, mixed $idEtudiant)
    {
        $sql = "SELECT * FROM Supervise WHERE idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND statut = 'en attente'";
        return self::FetchAssoc($sql, [
            'idUtilisateur' => $getIdutilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getNomsTuteurs(): array
    {
        $resultat = self::FetchAll("SELECT nom, prenom,st.idutilisateur FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur") ?? [];
        $list = [];
        foreach ($resultat as $tuteur) {
            $list[$tuteur["idutilisateur"]] = $tuteur["prenom"] . " " . $tuteur["nom"];
        }
        return $list;
    }

    public function role(): Roles
    {
        return Roles::Tutor;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idutilisateur): ?Tuteur
    {
        $sql = "SELECT * FROM " . self::$view . " WHERE idUtilisateur = :idUtilisateur";
        $resultat = self::FetchAssoc($sql, ['idUtilisateur' => $idutilisateur]);
        return $resultat ? $this->construireDepuisTableau($resultat) : null;
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Tuteur
    {
        return new Tuteur(
            $dataObjectFormatTableau
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfTuteurAlreadyExist($idUtilisateur, $idOffre, $idEtudiant): bool
    {
        $sql = "SELECT * FROM Supervise s JOIN Staff st ON st.idUtilisateur = s.idUtilisateur WHERE s.idUtilisateur = :idUtilisateur AND idOffre = :idOffre AND idEtudiant = :idEtudiant AND role = 'tuteurprof' AND statut = 'validee'";
        $resultat = self::FetchAssoc($sql, [
            'idUtilisateur' => $idUtilisateur,
            'idOffre' => $idOffre,
            'idEtudiant' => $idEtudiant
        ]);
        if ($resultat == null || $resultat["statut"] == "en attente" || $resultat["statut"] == "refusee") {
            return false;
        }
        return true;
    }

    /**
     * @throws ServerErrorException
     */
    public function getNomTuteurByIdEtudiantAndIdOffre(int $idetudiant, int $idoffre): ?string
    {
        $resultat = self::FetchAssoc("SELECT nom, prenom FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre", [
            'idEtudiant' => $idetudiant,
            'idOffre' => $idoffre
        ]);
        return $resultat ? $resultat["prenom"] . " " . $resultat["nom"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public function getTuteurByIdEtudiantAndIdOffre(int $idetudiant, int $idoffre): ?array
    {
        return self::FetchAssoc("SELECT * FROM Supervise s JOIN StaffVue st ON s.idUtilisateur = st.idUtilisateur WHERE idEtudiant = :idEtudiant AND idOffre = :idOffre", [
            'idEtudiant' => $idetudiant,
            'idOffre' => $idoffre
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