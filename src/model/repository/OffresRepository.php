<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\dataObject\Roles;
use Exception;
use PDO;


class OffresRepository extends AbstractRepository
{
    private string $nomTable = "Offre";

    /**
     * @throws ServerErrorException
     */
    public static function getAllWithFilter($params): array
    {
        try {
            $filtres = [];
            $type = "";
            if (!Auth::has_role(Roles::Manager, Roles::Staff)) $filtres[] = "statut = 'valider' AND pourvue = 0";
            if (Auth::has_role(Roles::Enterprise, Roles::Tutor)) {
                $id = Application::getUser()->id();
                if (Auth::has_role(Roles::Tutor)) {
                    $tuteur = (new TuteurEntrepriseRepository([]))->getById($id);
                    $id = $tuteur->getIdentreprise();
                }
                $filtres[] = "idUtilisateur = " . $id;
            }
            if (isset($params['type']) && count($params['type']) == 1) {
                if ($params['type'][0] == 'alternance') $type = " JOIN OffreAlternance oa ON oa.idoffre = o.idoffre";
                else if ($params['type'][0] == 'stage') $type = " JOIN OffreStage os ON os.idoffre = o.idoffre";
            }
            if (isset($_GET['sujet']) && $_GET['sujet'] != "")
                $filtres[] = "sujet LIKE :sujet";
            if (isset($params['year']) && $params['year'] != "all") $filtres[] = "anneevisee=" . $params['year'];
            if (isset($params["duration"]) && $params["duration"] != "all") $filtres[] = "duree='" . $params["duration"] . "'";
            if (isset($params["theme"]) && count($params["theme"]) > 0) $filtres[] = self::constructFilterFromArray("thematique", $params["theme"]);
            if (isset($params["gratification"]))
                $filtres[] = "gratification BETWEEN " . $params["gratification"]["min"] . " AND " . $params["gratification"]["max"];
            $condition = implode(" AND ", $filtres);
            $where = count($filtres) > 0 && $condition !== "" ? " WHERE " . $condition : "";
            $sql = "SELECT o.idoffre, thematique, sujet, datecreation, statut, u.nom, anneevisee, LEFT(o.description, 50) AS description  FROM Offre o JOIN Utilisateur u ON o.idUtilisateur = u.idUtilisateur " . $type . $where;
            $requete = Database::get_conn()->prepare($sql);
            if (isset($_GET['sujet']) && $_GET['sujet'] != "")
                $requete->execute([':sujet' => '%' . strip_tags($_GET['sujet']) . '%']);
            else $requete->execute();
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            return $requete->fetchAll();
        } catch (Exception) {
            throw new ServerErrorException("Erreur lors de la récupération des offres");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getById($idOffre): ?Offre
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idoffre = :idoffre";
        $data = self::FetchAssoc($sql, ['idoffre' => $idOffre]);
        return $data ? $this->construireDepuisTableau($data) : null;
    }

    /**
     * @throws ServerErrorException
     */
    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Offre
    {
        return new Offre(
            $dataObjectFormatTableau
        );
    }

    private static function constructFilterFromArray($column, $types): string
    {
        if (empty($types))
            return "";
        $filters = [];
        foreach ($types as $type)
            $filters[] = $column . "='" . $type . "'";
        return "(" . implode(" OR ", $filters) . ")";
    }

    /**
     * @throws ServerErrorException
     */
    public static function getInfosForNewsletter($id): ?array
    {
        return self::FetchAssoc("SELECT o.anneevisee, o.thematique, os.idoffre AS est_stage, oa.idoffre AS est_alternance
            FROM offre o
            LEFT JOIN offrestage os ON o.idoffre = os.idoffre
            LEFT JOIN offrealternance oa ON o.idoffre = oa.idoffre
            WHERE o.idoffre = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getAllByEnterprise(): null|array
    {
        $id = Application::getUser()->id();
        if (Auth::has_role(Roles::Tutor)) {
            $tuteur = (new TuteurEntrepriseRepository([]))->getById($id);
            $id = $tuteur->getIdentreprise();
        }
        return self::FetchAllAssoc("SELECT idOffre, sujet, thematique, datecreation, statut FROM Offre WHERE idUtilisateur = :idUtilisateur", ['idUtilisateur' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getOffresByIdEntreprisePublic(mixed $id): bool|array
    {
        return self::FetchAllAssoc("SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur AND statut = 'valider'", ['idUtilisateur' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getSujetOffre($getIdOffre)
    {
        return self::Fetch("SELECT sujet FROM Offre WHERE idOffre = :idOffre", ['idOffre' => $getIdOffre]);
    }

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        $sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur";
        return self::FetchAllAssoc($sql);
    }

    /**
     * @throws ServerErrorException
     */
    public function deleteById($idOffre): bool
    {
        $sql = "DELETE FROM $this->nomTable WHERE idoffre = :idoffre";
        self::Execute($sql, ['idoffre' => $idOffre]);
        return true;
    }

    /**
     * @throws ServerErrorException
     */
    public function updateToArchiver($idOffre): bool
    {
        $sql = "UPDATE $this->nomTable SET statut = 'archiver' WHERE idoffre = :idoffre";
        self::Execute($sql, ['idoffre' => $idOffre]);
        return true;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getByIdWithUser($idOffre): ?Offre
    {
        $sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur WHERE idoffre = :idoffre";
        $data = self::FetchAssoc($sql, ['idoffre' => $idOffre]);
        return $data ? $this->construireDepuisTableau($data) : null;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getOffresByIdEntreprise($idEntreprise): ?array
    {
        $data = self::FetchAllAssoc("SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur", ['idUtilisateur' => $idEntreprise]) ?? [];
        $offres = [];
        foreach ($data as $offre_data)
            $offres[] = $this->construireDepuisTableau($offre_data);
        return $offres;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function draftExist($idEntreprise): array
    {
        $data = self::FetchAllAssoc("SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur AND statut = 'brouillon'", ['idUtilisateur' => $idEntreprise]) ?? [];
        $offres = [];
        foreach ($data as $offre_data)
            $offres[] = $this->construireDepuisTableau($offre_data);
        return $offres;
    }

    public
    function tableChecker($filter): string
    {
        if (array_key_exists('alternance', $filter) && array_key_exists('stage', $filter))
            return OffresRepository::getNomTable();
        else if (array_key_exists('alternance', $filter) && $filter['alternance'] != "")
            return "OffreAlternance JOIN Offre ON OffreAlternance.idoffre = Offre.idoffre";
        else if (array_key_exists('stage', $filter) && $filter['stage'] != "")
            return "OffreStage JOIN Offre ON OffreStage.idoffre = Offre.idoffre";
        else return OffresRepository::getNomTable();
    }

    protected
    function getNomTable(): string
    {
        return $this->nomTable;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function updateToApproved(mixed $id): void
    {
        $sql = "UPDATE $this->nomTable SET statut = 'valider' WHERE idoffre = :idoffre";
        self::Execute($sql, ['idoffre' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public
    function checkIfCreatorOffreIsArchived(Offre $offre): bool
    {
        $sql = "SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre";
        $data = self::Fetch($sql, ['idoffre' => $offre->getIdoffre()]);
        return $data && $data['archiver'] == 1;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function checkArchived(Offre $offre): bool
    {
        $sql = "SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre";
        $data = self::Fetch($sql, ['idoffre' => $offre->getIdoffre()]);
        return $data && $data['archiver'] == 1;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function checkIfUserPostuled(Offre $offre): bool
    {
        $sql = "SELECT * FROM Postuler WHERE idoffre = :idoffre AND idUtilisateur = :idUtilisateur";
        return self::Fetch($sql, ['idoffre' => $offre->getIdoffre(), 'idUtilisateur' => Auth::get_user()->id()]) !== null;
    }

    /**
     * @throws ServerErrorException
     */
    public function getTop5DomainesPlusDemandes(): false|array
    {
        return self::FetchAll("SELECT * FROM top_5_domaines_plus_demandes_cache;") ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public function getMoyenneCandidaturesParOffreParDomaine(): false|array
    {
        return self::FetchAll("SELECT * FROM moyenne_candidatures_par_offre_par_domaine_cache;") ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public function getStatsDistributionDomaine(): false|array
    {
        return self::FetchAll("SELECT * FROM distribution_stage_alternance_par_domaine_cache;") ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public function getStatsDensembleStageEtAlternance(): false|array
    {
        return self::FetchAll("SELECT * FROM obtenir_vue_ensemble_stage_et_alternance_cache;") ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public function getOffresDernierSemaine(): false|array
    {
        return self::FetchAll("SELECT * FROM obtenir_offres_dernier_semaine_cache;") ?? [];
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "idoffre",
            "duree",
            "thematique",
            "sujet",
            "nbJourTravailHebdo",
            "nbheureTravailhebdo",
            "gratification",
            "avantageNature",
            "dateDebut",
            "dateFin",
            "statut",
            "pourvue",
            "anneeVisee",
            "annee",
            "idUtilisateur",
            "dateCreation",
            "description"
        ];
    }

    protected
    function checkFilterNotEmpty(array $filter): bool
    {
        foreach ($filter as $value) if ($value != "") return true;
        return false;
    }
}
