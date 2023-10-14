<?php

namespace app\src\controller;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\Form\FormModel;
use app\src\model\OffreForm;
use app\src\model\repository\CandidatureRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurProRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\Request;
use app\src\model\Users\Roles;

/**
 * @throws ForbiddenException
 * @throws NotFoundException
 * @throws ServerErrorException
 */
class MainController extends Controller
{
    public function __construct()
    {
        //$this->registerMiddleware(new AuthMiddleware());
    }

    public function user_test(Request $req)
    {
        if (session_status() !== PHP_SESSION_NONE)
            session_destroy();
        $user = Auth::load_user_by_id($req->getRouteParams()["id"]);
        Auth::generate_token($user, "true");
        Application::$app->response->redirect('/');
    }

    public function contact(): string
    {
        return $this->render('contact');
    }

    public function profile(Request $req): string
    {
        $id = $req->getRouteParams()["id"] ?? null;
        if (!is_null($id)) {
            $user = Auth::load_user_by_id($id);
            if (is_null($user)) throw new NotFoundException();
        } else {
            $user = Application::getUser();
            if (is_null($user)) throw new ForbiddenException();
        }
        if ($user->role() === Roles::Enterprise) throw new NotFoundException();
        return $this->render('profile/profile', [
            'user' => $user
        ]);
    }

    public function archiver(Request $req): string
    {
        $user = (new UtilisateurRepository())->getUserById($req->getRouteParams()["id"]);
        if ((new UtilisateurRepository())->isArchived($user)) {
            (new UtilisateurRepository())->setUserToArchived($user, false);
            (new MailRepository())->send_mail([$user->getEmailutilisateur()], "Désarchivage de votre compte", "Votre compte a été désarchivé");
        } else {
            (new UtilisateurRepository())->setUserToArchived($user, true);
            (new MailRepository())->send_mail([$user->getEmailutilisateur()], "Archivage de votre compte", "Votre compte a été archivé");
        }
        Application::$app->response->redirect('/utilisateurs/' . $req->getRouteParams()["id"]);
        return '';
    }

    public function edit_profile(Request $request): string
    {
        if (Application::isGuest()) throw new ForbiddenException();
        $id = $request->getRouteParams()["id"] ?? null;
        if (!is_null($id) && !Auth::has_role(Roles::Manager, Roles::Staff))
            throw new ForbiddenException();
        $user = is_null($id) ? Application::getUser() : Auth::load_user_by_id($id);
        if (is_null($user)) throw new NotFoundException();
        $attr = [];
        switch ($user->role()) {
            case Roles::Enterprise:
                $attr = array_merge($attr, [
                    "name" => FormModel::string("Nom entreprise")->required()->default($user->attributes()["nomutilisateur"]),
                    "email" => FormModel::email("Adresse mail")->required()->default($user->attributes()["emailutilisateur"]),
                    "phone" => FormModel::phone("Téléphone")->default($user->attributes()["numtelutilisateur"]),
                ]);
                break;
            case Roles::Tutor:
                $attr = array_merge($attr, [
                    "name" => FormModel::string("Prénom")->required()->default($user->attributes()["nomutilisateur"]),
                    "surname" => FormModel::string("Nom")->required()->default($user->attributes()["prenomtuteurp"]),
                    "email" => FormModel::string("Adresse mail")->required()->default($user->attributes()["emailutilisateur"]),
                    "fonction" => FormModel::select("Fonction", [
                        "tuteur" => "Tuteur",
                        "responsable" => "Responsable"
                    ])->required()->default($user->attributes()["fonctiontuteurp"]),
                ]);
                break;
            case  Roles::Student:
                $attr = array_merge($attr, [
                    "email" => FormModel::email("Adresse mail perso")->default($user->attributes()["mailperso"]),
                    "tel" => FormModel::phone("Téléphone")->numeric()->default($user->attributes()["numtelutilisateur"]),
                    "date" => FormModel::date("Date de naissance")->default($user->attributes()["datenaissance"])->before(new \DateTime()),
                    "studentnum" => FormModel::string("Numéro Etudiant")->default($user->attributes()["numetudiant"]),
                ]);
                break;
            case Roles::Teacher:
            case Roles::Manager:
            case Roles::Staff:
                $attr = array_merge($attr, [
                    "role" => FormModel::select("Role", [
                        "responsable" => "Responsable",
                        "enseignant" => "Enseignant",
                        "secretariat" => "Secretariat"
                    ])->required()->default($user->attributes()["role"]),
                ]);
                break;
        }
        $attr = array_merge(
            ["picture" => FormModel::file("Photo de profile")->id("image")->image()],
            $attr,
            ["bio" => FormModel::string("Biographie")->default($user->attributes()["bio"])->max(200)]
        );
        $form = new FormModel($attr);
        $form->useFile();

        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $picture = $form->getFile("picture");
                if (!is_null($picture)) $picture->save("pictures", $user->id());
                $user->update($form->getParsedBody());
                Application::redirectFromParam('/profile');
                return '';
            }

        }
        return $this->render('profile/edit_profile', [
            'form' => $form
        ]);
    }

    public function dashboard(): string
    {
        return $this->render('dashboard/dashboard');
    }

    public function utilisateurs(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $utilisateur = null;
        if ((new EntrepriseRepository())->getByIdFull($id) != null) {
            $utilisateur = (new EntrepriseRepository())->getByIdFull($id);
            return $this->render('utilisateurs/detailEntreprise', ['utilisateur' => $utilisateur]);
        } elseif ((new EtudiantRepository())->getByIdFull($id) != null) {
            $utilisateur = (new EtudiantRepository())->getByIdFull($id);
            return $this->render('utilisateurs/detailEtudiant', ['utilisateur' => $utilisateur]);
        } elseif ((new TuteurRepository())->getByIdFull($id) != null) {
            $utilisateur = (new TuteurRepository())->getByIdFull($id);
            return $this->render('utilisateurs/detailTuteur', ['utilisateur' => $utilisateur]);
        } elseif ((new StaffRepository())->getByIdFull($id) != null) {
            $utilisateur = (new StaffRepository())->getByIdFull($id);
            return $this->render('utilisateurs/detailStaff', ['utilisateur' => $utilisateur]);
        }
        $utilisateur = (new UtilisateurRepository())->getAll();
        return $this->render('utilisateurs/utilisateurs', ['utilisateurs' => $utilisateur]);
    }

    public function entreprises(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $entreprise = (new EntrepriseRepository())->getByIdFull($id);
        if ($entreprise == null && $id != null) throw new NotFoundException();
        else if ($entreprise != null && $id != null) {
            $offres = (new OffresRepository())->getOffresByIdEntreprise($id);
            return $this->render('entreprise/detailEntreprise', ['entreprise' => $entreprise, 'offres' => $offres]);
        }

        $entreprises = (new EntrepriseRepository())->getAll();
        return $this->render('entreprise/entreprise', ['entreprises' => $entreprises]);
    }

    public function ListeTuteurPro(Request $request): string
    {
        $id = Application::getUser()->id();
        $tuteurs = (new TuteurProRepository())->getAllTuteursByIdEntreprise($id);
        return $this->render('tuteurPro/listeTuteurPro', ['tuteurs' => $tuteurs]);
    }

    public function postuler(Request $request): string
    {
        if (!Auth::has_role(Roles::Student)) throw new ForbiddenException();
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getById($id);

        if (!$offre) throw  new NotFoundException();

        $form = new FormModel([
            "cv" => FormModel::file("CV")->required()->pdf(),
            "ltm" => FormModel::file("Lettre de motivation")->required()->pdf()
        ]);
        $form->useFile();

        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $path = "uploads/" . $id . "_" . Application::getUser()->id();
                if (!$form->getFile("cv")->save($path, "cv") ||
                    !$form->getFile("ltm")->save($path, "ltm")) {
                    $form->setError("Impossible de télécharger tous les fichiers");
                    return '';
                }
                $stmt = Database::get_conn()->prepare("INSERT INTO `Candidature`(`idoffre`, `idutilisateur`) VALUES (?,?)");
                $stmt->execute([$id, Application::getUser()->id()]);
                Application::$app->response->redirect('/offres');
            }

        }
        return $this->render('candidature/postuler', [
            'form' => $form
        ]);
    }

    public function candidatures(Request $request): string
    {


        $id = $request->getRouteParams()['id'] ?? null;
        $candidatures = (new CandidatureRepository())->getById($id);
        if ($candidatures != null && $id != null) {
            return $this->render('candidature/detailCandidature', ['candidatures' => $candidatures]);
        }

        $candidaturesrepose = new CandidatureRepository();
        $candidatures = ($candidaturesrepose->getAll());

        if ($request->getMethod() === 'post') {
            $id = $request->getBody()['idcandidature'] ?? null;
            if ($request->getBody()['action'] === 'Accepter') {
                $sql = "UPDATE Candidature SET etatcandidature='Validé par secrétariat' WHERE idcandidature=$id";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute();
                $candidaturesrepose = new CandidatureRepository();
                $candidatures = ($candidaturesrepose->getAll());
                return $this->render('candidature/listCandidatures', ['candidatures' => $candidatures]);
            } else {
                $sql = "UPDATE Candidature SET etatcandidature='Refusé' WHERE idcandidature=$id";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute();
                $candidaturesrepose = new CandidatureRepository();
                $candidatures = ($candidaturesrepose->getAll());
                return $this->render('candidature/listCandidatures', ['candidatures' => $candidatures]);
            }
        }
        return $this->render('candidature/listCandidatures', ['candidatures' => $candidatures]);
    }

	public function creeroffre(Request $request): string
	{
		if ($request->getMethod() === 'get') {
			return $this->render('/offres/create');
		} else {
            $action=$_POST['action'];

            if($action == 'Supprimer Brouillon'){
                $idOffre = $_POST['id_offre'];
                OffreForm::deleteOffre($idOffre);
                return $this->render('/offres/create');
            }

            $type = $_POST['radios'];
            $titre = $_POST['titre'];
            $theme = $_POST['theme'] ?? null;
            $nbjour = $_POST['nbjour'] ?? null;
            $nbheure = $_POST['nbheure'];
			if ($type == "alternance") $distanciel = $_POST['distanciel'];
			else $distanciel = null;
			$salaire = $_POST['salaire'];
			$unitesalaire = "heures";
            if (isset($action)) {
                if ($action == 'Envoyer') {
                    $statut= "pending";
                } else if ($action == 'sauvegarder') {
                    $statut= "draft";
                }
            }
			$avantage = $_POST['avantage'];
			$dated = $_POST['dated'] ?? null;
            if($dated == null)
                $dated = date("Y-m-d H:i:s");
            $datef = $_POST['datef'] ?? null;
            if ($datef == null)
                $datef = date("Y-m-d H:i:s");
            $duree = $_POST['duree'] ?? null;
            $description = $_POST['description'];
            if (Application::getUser()->role() === Roles::Enterprise)
                $idUtilisateur = Application::getUser()->id();
            else
                $idUtilisateur = $_POST['entreprise'];
			$idOffre = $_POST['id_offre'];
            echo $idOffre;
			if ($duree == 1) {
				$anneeVisee = "2";
			} else {
				$anneeVisee = "3";
			}
			$idAnnee = date("Y");

			$datecreation = date("Y-m-d H:i:s");
            if($idOffre===""){
                $offre = new Offre(null, $duree, $theme, $titre, $nbjour, $nbheure, $salaire, $unitesalaire, $avantage, $dated, $datef,$statut, $anneeVisee, $idAnnee, $idUtilisateur, $description, $datecreation,null);
                OffreForm::creerOffre($offre, $distanciel);
            }
            else{
                $offre = new Offre($idOffre, $duree, $theme, $titre, $nbjour, $nbheure, $salaire, $unitesalaire, $avantage, $dated, $datef,$statut, $anneeVisee, $idAnnee, $idUtilisateur, $description, $datecreation,null);
                OffreForm::updateOffre($offre, $distanciel);
            }
			return $this->render('/offres/create');
		}
	}

    public function archiveOffre(Request $request): string
    {
        if (!Auth::has_role(Roles::Staff, Roles::Manager)) {
            throw new ForbiddenException();
        } else {
            $id = $request->getRouteParams()['id'] ?? null;
            $offre = (new OffresRepository())->getById($id);
            if ($offre == null && $id != null) throw new NotFoundException();

            if ($request->getMethod() === 'post') {
                $url = $_POST['link'];
                if ($offre == null && $id != null) throw new NotFoundException();
                else if ($offre != null && $id != null) {
                    (new OffresRepository())->updateToDraft($id);
                    Application::$app->response->redirect($url);
                }
            } elseif ($request->getMethod() === 'get') {
                (new OffresRepository())->updateToDraft($id);
                $offre = (new OffresRepository())->getByIdWithUser($id);
                return $this->render('offres/detailOffre', ['offre' => $offre]);
            }
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }
    }

    public function editOffre(Request $request): string
    {
        if (!Auth::has_role(Roles::Staff, Roles::Manager)) {
            throw new ForbiddenException();
        } else {
            $id = $request->getRouteParams()['id'] ?? null;
            $offre = (new OffresRepository())->getById($id);
            if ($offre == null && $id != null) throw new NotFoundException();
            $attr = [];
            $attr = array_merge($attr, [
                "sujet" => FormModel::string("Sujet")->default($offre->getSujet()),
                "thematique" => FormModel::string("Thématique")->default($offre->getThematique()),
                "nbjourtravailhebdo" => FormModel::int("Nombre de jours de travail hebdomadaire")->default($offre->getNbjourtravailhebdo()),
                "nbHeureTravailHebdo" => FormModel::double("Nombre d'heures de travail hebdomadaire")->default($offre->getNbHeureTravailHebdo()),
                "gratification" => FormModel::double("Gratification")->default($offre->getGratification()),
                "unitegratification" => FormModel::string("Unité de la gratification")->default($offre->getUnitegratification()),
                "avantageNature" => FormModel::string("Avantage en nature")->default($offre->getAvantageNature()),
                "anneeVisee" => FormModel::string("Année visée")->default($offre->getAnneeVisee()),
                "description" => FormModel::string("Description")->default($offre->getDescription()),
                "dateDebut" => FormModel::date("Date de début")->default($offre->getDateDebut())->id("dateDebut"),
                "dateFin" => FormModel::date("Date de fin")->default($offre->getDateFin())->id("dateFin"),
            ]);
            $form = new FormModel($attr);
            return $this->render('/offres/edit', ['offre' => $offre, 'form' => $form]);
        }
    }

    public function validateOffre(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getByIdWithUser($id);
        if ($offre == null && $id != null) throw new NotFoundException();

        if ($request->getMethod() === 'get') {
            (new OffresRepository())->updateToApproved($id);
            $offre = (new OffresRepository())->getByIdWithUser($id);
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }
        return $this->render('offres/detailOffre', ['offre' => $offre]);
    }

    public function offres(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getByIdWithUser($id);

        if ($offre == null && $id != null) throw new NotFoundException();
        else if ($offre != null && $id != null) {
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }

        $filter = self::constructFilter();
        if (empty($search) && empty($filter)) $offres = (new OffresRepository())->getAll();
        else $offres = (new OffresRepository())->search($filter);

        $userIdList = [];
        foreach ($offres as $offre) $userIdList[] = $offre->getIdutilisateur();
        $utilisateurRepository = new UtilisateurRepository();
        $utilisateurs = array();

        if (!empty($userIdList)) {
            foreach ($userIdList as $userId) {
                if (!isset($utilisateurs[$userId])) {
                    $utilisateur = $utilisateurRepository->getUserById($userId);
                    $utilisateurs[$userId] = $utilisateur->getNomutilisateur();
                }
            }
        }

        $currentFilterURL = "/offres?" . http_build_query($filter);
        return $this->render('offres/listOffres', ['offres' => $offres, 'utilisateurs' => $utilisateurs, 'currentFilterURL' => $currentFilterURL]);
    }

    private static function constructFilter(): array
    {
        return array(
            'sujet' => $_GET['sujet'] ?? "",
            'thematique' => isset($_GET['thematique']) ? implode(',', $_GET['thematique']) : "",
            'anneeVisee' => $_GET['anneeVisee'] ?? null,
            'duree' => $_GET['duree'] ?? null,
            'alternance' => $_GET['alternance'] ?? null,
            'stage' => $_GET['stage'] ?? null,
            'gratificationMin' => self::filterGratification($_GET['gratificationMin'] ?? null),
            'gratificationMax' => self::filterGratification($_GET['gratificationMax'] ?? null)
        );
    }

    private static function filterGratification($value)
    {
        if ($value === "") return null;
        return min(max((float)$value, 4.05), 15);
    }

}
