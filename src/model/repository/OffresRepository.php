<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\dataObject\Roles;
use PDOException;


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
		try {
			$sql = "SELECT * FROM $this->nomTable WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $idOffre]);
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			$resultat = $requete->fetch();
			if ($resultat == false) return null;
			return $this->construireDepuisTableau($resultat);
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	protected
	function construireDepuisTableau(array $dataObjectFormatTableau): Offre
	{
		return new Offre(
			$dataObjectFormatTableau['idoffre'],
			$dataObjectFormatTableau['duree'],
			$dataObjectFormatTableau['thematique'],
			$dataObjectFormatTableau['sujet'],
			$dataObjectFormatTableau['nbjourtravailhebdo'],
			$dataObjectFormatTableau['nbheuretravailhebdo'],
			$dataObjectFormatTableau['gratification'],
			$dataObjectFormatTableau['avantagesnature'],
			$dataObjectFormatTableau['datedebut'],
			$dataObjectFormatTableau['datefin'],
			$dataObjectFormatTableau['statut'],
			$dataObjectFormatTableau['pourvue'],
			$dataObjectFormatTableau['anneevisee'],
			$dataObjectFormatTableau['annee'],
			$dataObjectFormatTableau['idutilisateur'],
			$dataObjectFormatTableau['datecreation'],
			$dataObjectFormatTableau['description']
		);
	}

	private static function constructFilterFromArray($column, $types)
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
	public static function getAllByEnterprise(): array
	{
		try {
			$id = Application::getUser()->id();
			if (Auth::has_role(Roles::Tutor)) {
				$tuteur = (new TuteurEntrepriseRepository([]))->getById($id);
				$id = $tuteur->getIdentreprise();
			}
			$sql = "SELECT idOffre, sujet, thematique, datecreation, statut FROM Offre WHERE idUtilisateur = :idUtilisateur";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idUtilisateur' => $id]);
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			return $requete->fetchAll();
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getAll(): ?array
	{
		try {
			$sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute();
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			$resultat = $requete->fetchAll();
			if ($resultat == false) return null;
			return $resultat;
		} catch (PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function deleteById($idOffre): bool
	{
		try {
			$sql = "DELETE FROM $this->nomTable WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $idOffre]);
			return true;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function updateToArchiver($idOffre): bool
	{
		try {
			$sql = "UPDATE $this->nomTable SET statut = 'archiver' WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $idOffre]);
			return true;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getByIdWithUser($idOffre): ?Offre
	{
		try {
			$sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $idOffre]);
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			$resultat = $requete->fetch();
			if (!$resultat) return null;
			return $this->construireDepuisTableau($resultat);
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function getOffresByIdEntreprise($idEntreprise): ?array
	{
		try {
			$sql = "SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idUtilisateur' => $idEntreprise]);
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			$resultat = $requete->fetchAll();
			if ($resultat == false) return null;
			$offres = [];
			foreach ($resultat as $offre_data) {
				$offres[] = $this->construireDepuisTableau($offre_data);
			}
			return $offres;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function draftExist($idEntreprise): array
	{
		try {
			$sql = "SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur AND statut = 'brouillon'";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idUtilisateur' => $idEntreprise]);
			$requete->setFetchMode(\PDO::FETCH_ASSOC);
			$resultat = $requete->fetchAll();
			$offres = [];
			if (!$resultat) return $offres;
			foreach ($resultat as $offre_data) {
				$offres[] = $this->construireDepuisTableau($offre_data);
			}
			return $offres;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
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
	function updateToApproved(mixed $id)
	{
		try {
			$sql = "UPDATE $this->nomTable SET statut = 'valider' WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $id]);
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function checkIfCreatorOffreIsArchived(Offre $offre): bool
	{
		try {
			$sql = "SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $offre->getIdoffre()]);
			$resultat = $requete->fetch();
			if ($resultat['archiver'] == 1) return true;
			else return false;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function checkArchived(Offre $offre): bool
	{
		try {
			$sql = "SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $offre->getIdoffre()]);
			$resultat = $requete->fetch();
			if ($resultat['archiver'] == 1) return true;
			else return false;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public
	function checkIfUserPostuled(Offre $offre): bool
	{
		try {
			$sql = "SELECT * FROM Postuler WHERE idoffre = :idoffre AND idUtilisateur = :idUtilisateur";
			$requete = Database::get_conn()->prepare($sql);
			$requete->execute(['idoffre' => $offre->getIdoffre(), 'idUtilisateur' => Auth::get_user()->id()]);
			$resultat = $requete->fetch();
			if ($resultat == false) return false;
			else return true;
		} catch
		(PDOException) {
			throw new ServerErrorException();
		}
	}

    public function getTop5DomainesPlusDemandes(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM top_5_domaines_plus_demandes_cache;")->fetchAll();
    }

    public function getMoyenneCandidaturesParOffreParDomaine(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM moyenne_candidatures_par_offre_par_domaine_cache;")->fetchAll();
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

    /**
     * @throws ServerErrorException
     */
    public function getStatsDistributionDomaine(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM distribution_stage_alternance_par_domaine_cache;")->fetchAll();
    }

    /**
     * @throws ServerErrorException
     */
    public function getStatsDensembleStageEtAlternance(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM obtenir_vue_ensemble_stage_et_alternance_cache;")->fetch();
    }

    /**
     * @throws ServerErrorException
     */
    public function getOffresDernierSemaine(): false|array
    {
        return Database::get_conn()->query("SELECT * FROM obtenir_offres_dernier_semaine_cache;")->fetchAll();
    }

	protected
	function checkFilterNotEmpty(array $filter): bool
	{
		foreach ($filter as $key => $value) if ($value != "") return true;
		return false;
	}
}
