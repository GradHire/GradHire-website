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
    /**
     * @throws ServerErrorException
     */
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

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
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
        return $this->render('/profile', [
            'user' => $user,
            'form' => null
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

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function edit_profile(Request $request): string
    {
        if (Application::isGuest()) throw new ForbiddenException();
        $id = $request->getRouteParams()["id"] ?? null;
        if (!is_null($id))
            if (!Auth::has_role(Roles::Manager, Roles::Staff) && !(Application::getUser()->role() == Roles::Enterprise && Application::getUser()->id() == $id))
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
        return $this->render('profile', [
            'form' => $form,
            'user' => $user
        ]);
    }

    public function dashboard(): string
    {
        return $this->render('dashboard/dashboard');
    }

    public function utilisateurs(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        if (!is_null($id) && !Auth::has_role(Roles::Manager, Roles::Staff)) throw new ForbiddenException();
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

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function entreprises(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $entreprise = (new EntrepriseRepository())->getByIdFull($id);
        if ($entreprise == null && $id != null) throw new NotFoundException();
        else if ($entreprise != null && $id != null) {
            $offres = (new OffresRepository())->getOffresByIdEntreprise($id);
            return $this->render('entreprise/detailEntreprise', ['entreprise' => $entreprise, 'offres' => $offres]);
        }

        if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::Enterprise, Roles::Student, Roles::Teacher)) {
            $entreprises = (new EntrepriseRepository())->getAll();
            return $this->render('entreprise/entreprise', ['entreprises' => $entreprises]);
        } else throw new ForbiddenException();
    }

    public function ListeTuteurPro(Request $request): string
    {
        if (!Auth::has_role(Roles::Manager, Roles::Enterprise, Roles::Staff)) {
            throw new ForbiddenException();
        }
        $tuteurs = [];
        if (Auth::has_role(Roles::Manager)) $tuteurs = (new TuteurProRepository())->getAll();
        else if (Auth::has_role(Roles::Enterprise)) $tuteurs = (new TuteurProRepository())->getAllTuteursByIdEntreprise(Application::getUser()->id());
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


    public function creeroffre(Request $request): string
    {
        if (!Auth::has_role(Roles::Manager, Roles::Enterprise, Roles::Staff)) {
            throw new ForbiddenException();
        }

        if ($request->getMethod() === 'get') {
            return $this->render('/offres/create');
        }

        $action = $_POST['action'];
        $type = $_POST['radios'];
        $theme = $_POST['theme'] ?? null;
        $nbjour = $_POST['nbjour'] ?? null;
        $nbheure = $_POST['nbheure'];
        $distanciel = $type == "alternance" ? $_POST['distanciel'] : null;
        $salaire = $_POST['salaire'];
        $unitesalaire = "heures";
        $avantage = $_POST['avantage'];
        $description = $_POST['description'];
        $dated = $_POST['dated'] ?? date("Y-m-d H:i:s");
        $datef = $_POST['datef'] ?? date("Y-m-d H:i:s");
        $duree = $_POST['duree'] ?? null;
        $idUtilisateur = Application::getUser()->role() === Roles::Enterprise ? Application::getUser()->id() : $_POST['entreprise'];
        $idOffre = $_POST['id_offre'];
        if ($idOffre === "") {
            $idOffre = null;
        }
        $idAnnee = date("Y");
        $datecreation = date("Y-m-d H:i:s");

        if ($action == 'Supprimer Brouillon') {
            OffreForm::deleteOffre($idOffre);
            return $this->render('/offres/create');
        }

        $titre = $_POST['titre'];
        $statut = $action == 'Envoyer' ? "pending" : "draft";

        $anneeVisee = $duree == 1 ? "2" : "3";

        $offre = new Offre($idOffre, $duree, $theme, $titre, $nbjour, $nbheure, $salaire, $unitesalaire, $avantage,
            $dated, $datef, $statut, $anneeVisee, $idAnnee, $idUtilisateur, $description, $datecreation, null);

        if ($idOffre === null) {
            OffreForm::creerOffre($offre, $distanciel);

        } else {
            OffreForm::updateOffre($offre, $distanciel);
        }

        return $this->render('/offres/create');
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
                (new OffresRepository())->updateToDraft($id);
                (new MailRepository())->send_mail([(new UtilisateurRepository())->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Archivage de votre offre", "Votre offre a été archivée");
                $offre = (new OffresRepository())->getByIdWithUser($id);
                return $this->render('offres/detailOffre', ['offre' => $offre]);
            } elseif ($request->getMethod() === 'get') {
                (new OffresRepository())->updateToDraft($id);
                (new MailRepository())->send_mail([(new UtilisateurRepository())->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Archivage de votre offre", "Votre offre a été archivée");
                Application::redirectFromParam("/offres");
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
            (new MailRepository())->send_mail([(new UtilisateurRepository())->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Modification de votre offre", "Votre offre a été modifiée");
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
            (new MailRepository())->send_mail([(new UtilisateurRepository())->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Validation de votre offre", "Votre offre a été validée");
            $offre = (new OffresRepository())->getByIdWithUser($id);
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }
        return $this->render('offres/detailOffre', ['offre' => $offre]);
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function offres(Request $request): string
    {
        if (!Auth::has_role(Roles::Staff, Roles::Manager, Roles::Enterprise, Roles::Teacher, Roles::Student, Roles::Tutor)) throw new ForbiddenException();

        elseif (Auth::has_role(Roles::Enterprise, Roles::Tutor) && !isset($request->getRouteParams()['id'])) {
            $id = Application::getUser()->id();
            if (Auth::has_role(Roles::Tutor)) {
                $tuteur = (new TuteurProRepository())->getById($id);
                $id = $tuteur->getIdentreprise();
            }
            $offres = (new OffresRepository())->getOffresByIdEntreprise($id);
            return $this->render('entreprise/offres', ['offres' => $offres]);
        }

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
        $filter = array();
//        if (Auth::has_role(["student"])) {
//            if (isset($_GET['statut'])) $filter['statut'] = $_GET['statut'];
//        } else {
//            $filter['statut'] = "staff";
//        }
        if (isset($_GET['sujet'])) $filter['sujet'] = $_GET['sujet'];
        else $filter['sujet'] = "";
        if (isset($_GET['thematique'])) {
            $filter['thematique'] = "";
            foreach ($_GET['thematique'] as $key => $value) {
                if ($filter['thematique'] == null) $filter['thematique'] = $value;
                else if ($filter['thematique'] != null) $filter['thematique'] .= ',' . $value;
            }
        }
        if (isset($_GET['anneeVisee'])) $filter['anneeVisee'] = $_GET['anneeVisee'];
        if (isset($_GET['duree'])) $filter['duree'] = $_GET['duree'];
        if (isset($_GET['alternance'])) $filter['alternance'] = $_GET['alternance'];
        if (isset($_GET['stage'])) $filter['stage'] = $_GET['stage'];
        $_GET['gratificationMin'] = 4.05;
        $_GET['gratificationMax'] = 15;
        if (isset($_GET['gratificationMin'])) {
            if ($_GET['gratificationMin'] == "") $filter['gratificationMin'] = null;
            else if ($_GET['gratificationMin'] < 4.05) $filter['gratificationMin'] = 4.05;
            else if ($_GET['gratificationMin'] > 15) $filter['gratificationMin'] = 15;
            else $filter['gratificationMin'] = $_GET['gratificationMin'];
        }
        if (isset($_GET['gratificationMax'])) {
            if ($_GET['gratificationMax'] == "") $filter['gratificationMax'] = null;
            else if ($_GET['gratificationMax'] < 4.05) $filter['gratificationMax'] = 4.05;
            else if ($_GET['gratificationMax'] > 15) $filter['gratificationMax'] = 15;
            else $filter['gratificationMax'] = $_GET['gratificationMax'];
        }
        return $filter;
    }

    public function candidatures(Request $request): string
    {
        $userid = Application::getUser()->id();

        $id = $request->getRouteParams()['id'] ?? null;
        $candidatures = (new CandidatureRepository())->getById($id);
        if ($candidatures != null && $id != null) {
            return $this->render('candidature/detailCandidature', ['candidatures' => $candidatures]);
        }
        if ($request->getMethod() === 'post') {
            $id = $request->getBody()['idcandidature'] ?? null;
            $candidature = (new CandidatureRepository())->getById($id);
            if ($request->getBody()['action'] === 'Accepter') {
                $candidature->setEtatcandidature("accepted");
            } else {
                $candidature->setEtatcandidature("declined");
            }
        }
        $array = [];
        if (Auth::has_role(Roles::Enterprise, Roles::Tutor)) {
            if (Auth::has_role(Roles::Tutor)) $entrepriseid = (new TuteurProRepository())->getById($userid)->getIdentreprise();
            else $entrepriseid = $userid;
            $array = ['candidaturesAttente' => (new CandidatureRepository())->getByIdEntreprise($entrepriseid, 'on hold'),
                'candidaturesAutres' => array_merge((new CandidatureRepository())->getByIdEntreprise($entrepriseid, 'accepted'), (new CandidatureRepository())->getByIdEntreprise($entrepriseid, 'declined'))
            ];
        } else if (Auth::has_role(Roles::Manager, Roles::Staff)) {

            $array = ['candidaturesAttente' => (new CandidatureRepository())->getByStatement('on hold'),
                'candidaturesAutres' => array_merge((new CandidatureRepository())->getByStatement('accepted'), (new CandidatureRepository())->getByStatement('declined'))
            ];
        } else if (Auth::has_role(Roles::Teacher)) {
            $array = ['candidaturesAutres' => (new CandidatureRepository())->getByStatement('accepted')];
        }
        return $this->render(
            'candidature/listCandidatures', $array);
    }
}
