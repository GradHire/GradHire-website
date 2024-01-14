<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Postuler;
use PDO;

class PostulerRepository extends AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    private static string $nomTable = "postulervue";

    /**
     * @throws ServerErrorException
     */
    public static function postuler($id): void
    {
        self::Execute("INSERT INTO Postuler(idoffre, idUtilisateur, dates) VALUES (?,?,?)", [
            $id,
            Application::getUser()->id(),
            date("Y-m-d H:i:s")
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByIdEntreprise($identreprise): ?array
    {
        $sql = "SELECT e.nom,e.sujet,e.dates,e.idOffre,e.idUtilisateur,e.idEntreprise,e.statut,u.nom AS nomEntreprise, et.email AS emailEtudiant FROM " . self::$nomTable . " e JOIN utilisateur u ON u.idUtilisateur = e.idEntreprise JOIN utilisateur et ON et.idUtilisateur = :id WHERE e.idEntreprise = :id";
        return self::FetchAllAssoc($sql, ['id' => $identreprise]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getCandidaturesAttenteEntreprise($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM " . self::$nomTable . " WHERE identreprise= :id AND statut::text LIKE 'en attente%'";
        return self::FetchAllAssoc($sql, ['id' => $identreprise]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getCandidaturesAttenteEtudiant($identreprise): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut,u.nom AS nomEntreprise FROM " . self::$nomTable . " JOIN utilisateur u ON u.idUtilisateur = idEntreprise WHERE idUtilisateur= :id AND statut::text LIKE 'en attente%'";
        return self::FetchAllAssoc($sql, ['id' => $identreprise]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByIdEtudiant($idEtudiant): ?array
    {
        $sql = "SELECT e.nom,e.sujet,e.dates,e.idOffre,e.idUtilisateur,e.idEntreprise,e.statut,u.nom AS nomEntreprise, et.email AS emailEtudiant FROM " . self::$nomTable . " e JOIN utilisateur u ON u.idUtilisateur = e.idEntreprise JOIN utilisateur et ON et.idUtilisateur = :id WHERE e.idUtilisateur= :id";
        return self::FetchAllAssoc($sql, ['id' => $idEtudiant]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getAllCandidatures(): ?array
    {
        $sql = "SELECT e.nom,e.sujet,e.dates,e.idOffre,e.idUtilisateur,e.idEntreprise,e.statut,u.nom AS nomEntreprise, et.email AS emailEtudiant,s.idUtilisateur AS idTutor FROM " . self::$nomTable . " e JOIN utilisateur u ON u.idUtilisateur = e.idEntreprise JOIN utilisateur et ON et.idUtilisateur = e.idUtilisateur LEFT JOIN Supervise s ON s.idOffre = e.idOffre";
        return self::FetchAllAssoc($sql) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByStatementValideeOrRefusee(): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM " . self::$nomTable . " WHERE statut::text='validee' OR statut::text='refusee'";
        return self::FetchAllAssoc($sql);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByStatementAttente(): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM " . self::$nomTable . " WHERE statut::text LIKE 'en attente%'";
        return self::FetchAllAssoc($sql);
    }

    /**
     * @throws ServerErrorException
     */
    public static function refuserCandidature(int $idutilisateur, mixed $idOffre): void
    {
        $sql = "UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur";
        self::Execute($sql, [
            'idoffre' => $idOffre,
            'idutilisateur' => $idutilisateur
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function validerCandidatureEtudiant(mixed $idEtudiant, mixed $idOffre): void
    {
        self::Execute("UPDATE Postuler SET statut = 'en attente tuteur prof' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur", [
            'idoffre' => $idOffre,
            'idutilisateur' => $idEtudiant
        ]);
        self::Execute("UPDATE Postuler SET statut = 'refusee' WHERE idOffre!=:idoffre AND idUtilisateur=:idutilisateur", [
            'idoffre' => $idOffre,
            'idutilisateur' => $idEtudiant
        ]);
        self::Execute("UPDATE Postuler SET statut = 'refusee' WHERE idOffre=:idoffre AND idUtilisateur!=:idutilisateur", [
            'idoffre' => $idOffre,
            'idutilisateur' => $idEtudiant
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function validerCandidatureEntreprise(int $idUtilisateur, int $idOffre): void
    {
        self::Execute("UPDATE Postuler SET statut = 'en attente etudiant' WHERE idOffre=:idoffre AND idUtilisateur=:idutilisateur", [
            'idoffre' => $idOffre,
            'idutilisateur' => $idUtilisateur
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByStatementAttenteTuteur(): array
    {
        $sql = "SELECT nom,sujet,dates,idOffre, idUtilisateur,idEntreprise,statut FROM " . self::$nomTable . " WHERE CAST(statut as TEXT) = 'en attente tuteur prof' OR CAST(statut as TEXT) = 'en attente responsable' OR CAST(statut as TEXT) = 'en attente tuteur entreprise'";
        return self::FetchAllAssoc($sql);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByStatementTuteur(int $idutilisateur, string $string): bool|array
    {
        if ($string == 'validee') {
            $sql = "SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur, su.idUtilisateur as idTuteur,idEntreprise,p.statut FROM " . self::$nomTable . " p JOIN Supervise su ON su.idOffre=p.idOffre WHERE su.statut::text = 'validee' AND su.idutilisateur=:idutilisateur AND CAST(p.statut AS TEXT) = CAST(su.statut AS TEXT)";
            return self::FetchAllAssoc($sql, ['idutilisateur' => $idutilisateur]);
        } else if ($string == 'refusee') {
            $sql = "SELECT nom,sujet,dates,p.idOffre, p.idUtilisateur, su.idUtilisateur as idTuteur,idEntreprise,p.statut FROM " . self::$nomTable . " p JOIN Supervise su ON su.idOffre=p.idOffre WHERE su.idUtilisateur = :idutilisateur AND CAST(p.statut AS TEXT) != CAST(su.statut AS TEXT) AND su.statut::text = 'refusee'";
            $data = self::FetchAll($sql, [
                'idutilisateur' => $idutilisateur
            ]);
            foreach ($data as $item)
                $item['statut'] = 'refusee';
            return $data;
        }
        return [];
    }

    /**
     * @throws ServerErrorException
     */
    public function getById($idOffre, $idUtilisateur): ?Postuler
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM " . self::$nomTable . " WHERE idOffre=:idOffre AND idUtilisateur=:idUtilisateur";
        $data = self::FetchAssoc($sql, [
            'idOffre' => $idOffre,
            'idUtilisateur' => $idUtilisateur
        ]);
        return $data ? $this->construireDepuisTableau($data) : null;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Postuler
    {
        return new Postuler(
            $dataObjectFormatTableau
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function setStatutPostuler(int $idutilisateur, int $idoffre, string $etat): void
    {
        $sql = "UPDATE " . self::$nomTable . " SET statut=:etat WHERE idUtilisateur=:idutilisateur AND idOffre=:idoffre";
        self::Execute($sql, [
            'etat' => $etat,
            'idutilisateur' => $idutilisateur,
            'idoffre' => $idoffre
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        return self::FetchAllAssoc("SELECT * FROM " . self::$nomTable);
    }

    /**
     * @throws ServerErrorException
     */
    public function getStatsCandidaturesParMois(): false|array
    {
        return self::FetchAll("SELECT * FROM candidatures_par_mois_cache;");
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfSuivi(int $idUtilisateur, $idetu, $idoffre): bool
    {
        return self::Fetch("SELECT idUtilisateur FROM Supervise WHERE idutilisateur = :idutilisateur AND idetudiant = :idetudiant AND idoffre = :idoffre", [
                'idutilisateur' => $idUtilisateur,
                'idetudiant' => $idetu,
                'idoffre' => $idoffre
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdOffre(mixed $idOffre): ?array
    {
        $sql = "SELECT nom,sujet,dates,idOffre,idUtilisateur,idEntreprise,statut FROM " . self::$nomTable . " WHERE idOffre=:idOffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idOffre' => $idOffre]);
        $requete->setFetchMode(PDO::FETCH_ASSOC);
        $resultat = [];
        foreach ($requete as $item) {
            $resultat[] = $this->construireDepuisTableau($item);
        }
        return $resultat;
    }

    /**
     * @throws ServerErrorException
     */
    public function getSiTuteurPostuler(?int $getIdUtilisateur, ?int $getIdOffre): bool
    {
        $sql = "SELECT idUtilisateur FROM Supervise WHERE idEtudiant=:idUtilisateur AND idOffre=:idOffre";
        return self::FetchAssoc($sql, [
                'idUtilisateur' => $getIdUtilisateur,
                'idOffre' => $getIdOffre
            ]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public function getTuteurByIdOffre(mixed $idOffre): ?array
    {
        $sql = "SELECT * FROM Supervise WHERE idOffre=:idOffre";
        return self::FetchAllAssoc($sql, ['idOffre' => $idOffre]);
    }

    /**
     * @throws ServerErrorException
     */
    public function getIfStudentAlreadyAccepted(int $idOffre): bool
    {
        $sql = "SELECT COUNT(idUtilisateur) as nbAccepter FROM " . self::$nomTable . " WHERE idOffre=:idoffre AND (statut::text = 'validee' OR statut::text = 'en attente tuteur' OR statut::text = 'en attente responsable')";
        $data = self::FetchAssoc($sql, [
            'idoffre' => $idOffre
        ]);
        return $data && $data["nbaccepter"] == 1;
    }

    public static function getIfValideeInArray(?array $candidatures): bool
    {
        foreach ($candidatures as $candidature)
            if ($candidature['statut'] == 'validee') return true;
        return false;
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "sujet",
            "nom",
            "dates",
            "idOffre",
            "idUtilisateur",
            "statut"
        ];
    }

    protected
    function getNomTable(): string
    {
        return "PostulerVue";
    }
}