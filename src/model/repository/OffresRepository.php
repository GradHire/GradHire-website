<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\dataObject\Roles;


class OffresRepository extends AbstractRepository
{
	private static int $count;
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
			if (isset($params["duration"]) && $params["duration"] != "all") $filtres[] = "duree=" . $params["duration"];
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
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			return $requete->fetchAll();
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getById($idOffre): ?Offre
	{
		$resultat = self::FetchAssoc("SELECT * FROM $this->nomTable WHERE idoffre = :idoffre", ['idoffre' => $idOffre]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

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
	public static function getInfosForNewsletter($id)
	{
		$sql = "
            SELECT o.anneevisee, o.thematique, os.idoffre AS est_stage, oa.idoffre AS est_alternance
            FROM offre o
            LEFT JOIN offrestage os ON o.idoffre = os.idoffre
            LEFT JOIN offrealternance oa ON o.idoffre = oa.idoffre
            WHERE o.idoffre = ?";
		return self::FetchAssoc($sql, [$id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getAllByEnterprise(): array
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
	public function getAll(): ?array
	{
		return self::FetchAllAssoc("SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur");
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function deleteById($idOffre): bool
	{
		self::Execute("DELETE FROM $this->nomTable WHERE idoffre = :idoffre", ['idoffre' => $idOffre]);
		return true;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function updateToArchiver($idOffre): bool
	{
		self::Execute("UPDATE OffreStage SET statut = 'archiver' WHERE idoffre = :idoffre", ['idoffre' => $idOffre]);
		return true;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getByIdWithUser($idOffre): ?Offre
	{
		$sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur WHERE idoffre = :idoffre";
		$resultat = self::FetchAssoc($sql, ['idoffre' => $idOffre]);
		return $resultat ? $this->construireDepuisTableau($resultat) : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getOffresByIdEntreprise($idEntreprise): ?array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur", ['idUtilisateur' => $idEntreprise]);
		if (!$resultat) return null;
		$offres = [];
		foreach ($resultat as $offre_data)
			$offres[] = $this->construireDepuisTableau($offre_data);
		return $offres;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function draftExist($idEntreprise): array
	{
		$resultat = self::FetchAllAssoc("SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur AND statut = 'brouillon'", ['idUtilisateur' => $idEntreprise]);
		$offres = [];
		if (!$resultat) return $offres;
		foreach ($resultat as $offre_data)
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
		self::Execute("UPDATE $this->nomTable SET statut = 'valider' WHERE idoffre = :idoffre", ['idoffre' => $id]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function checkIfCreatorOffreIsArchived(Offre $offre): bool
	{
		$resultat = self::Fetch("SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre", ['idoffre' => $offre->getIdoffre()]);
		return $resultat['archiver'] == 1;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function checkArchived(Offre $offre): bool
	{
		$resultat = self::Fetch("SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre", ['idoffre' => $offre->getIdoffre()]);
		return $resultat['archiver'] == 1;
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function checkIfUserPostuled(Offre $offre): bool
	{
		$resultat = self::Fetch("SELECT * FROM Postuler WHERE idoffre = :idoffre AND idUtilisateur = :idUtilisateur", ['idoffre' => $offre->getIdoffre(), 'idUtilisateur' => Auth::get_user()->id()]);
		if (!$resultat) return $resultat !== null;
		else return true;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getTop5DomainesPlusDemandes(): array
	{
		return self::FetchAll("SELECT * FROM top_5_domaines_plus_demandes_cache;") ?? [];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getMoyenneCandidaturesParOffreParDomaine(): array
	{
		return self::FetchAll("SELECT * FROM moyenne_candidatures_par_offre_par_domaine_cache;") ?? [];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getStatsDistributionDomaine(): array
	{
		return self::FetchAll("SELECT * FROM distribution_stage_alternance_par_domaine_cache;") ?? [];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getStatsDensembleStageEtAlternance(): array
	{
		return self::Fetch("SELECT * FROM obtenir_vue_ensemble_stage_et_alternance_cache;") ?? [];
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getOffresDernierSemaine(): array
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
}
