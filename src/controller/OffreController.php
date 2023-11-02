<?php

namespace app\src\controller;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\OffreForm;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\Request;

class OffreController extends AbstractController
{
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
                $tuteur = (new TuteurEntrepriseRepository([]))->getById($id);
                $id = $tuteur->getIdentreprise();
            } else {
                throw new ForbiddenException();
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
        $utilisateurRepository = new UtilisateurRepository([]);
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

    /**
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function validateOffre(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getByIdWithUser($id);
        if ($offre == null && $id != null) throw new NotFoundException();

        if ($request->getMethod() === 'get') {
            (new OffresRepository())->updateToApproved($id);
            (new MailRepository())->send_mail([(new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Validation de votre offre", "Votre offre a été validée");
            $offre = (new OffresRepository())->getByIdWithUser($id);
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }
        return $this->render('offres/detailOffre', ['offre' => $offre]);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
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
                "nbJourTravailHebdo" => FormModel::int("Nombre de jours de travail hebdomadaire")->default($offre->getNbjourtravailhebdo()),
                "nbHeureTravailHebdo" => FormModel::double("Nombre d'heures de travail hebdomadaire")->default($offre->getNbHeureTravailHebdo()),
                "gratification" => FormModel::double("Gratification")->default($offre->getGratification()),
                "avantageNature" => FormModel::string("Avantage en nature")->default($offre->getAvantageNature()),
                "anneeVisee" => FormModel::string("Année visée")->default($offre->getAnneeVisee()),
                "dateDebut" => FormModel::date("Date de début")->default($offre->getDateDebut())->id("dateDebut"),
                "dateFin" => FormModel::date("Date de fin")->default($offre->getDateFin())->id("dateFin"),
                "description" => FormModel::string("Description")->default($offre->getDescription()),
            ]);
            $form = new FormModel($attr);
            (new MailRepository())->send_mail([(new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Modification de votre offre", "Votre offre a été modifiée");
            return $this->render('/offres/edit', ['offre' => $offre, 'form' => $form]);
        }
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
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
                (new MailRepository())->send_mail([(new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Archivage de votre offre", "Votre offre a été archivée");
                $offre = (new OffresRepository())->getByIdWithUser($id);
                return $this->render('offres/detailOffre', ['offre' => $offre]);
            } elseif ($request->getMethod() === 'get') {
                (new OffresRepository())->updateToDraft($id);
                (new MailRepository())->send_mail([(new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Archivage de votre offre", "Votre offre a été archivée");
                Application::redirectFromParam("/offres");
            }
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
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
            $dated, $datef, $statut, $anneeVisee, $idAnnee, $idUtilisateur, $datecreation, $description);

        if ($idOffre === null) {
            OffreForm::creerOffre($offre, $distanciel);
        } else {
            OffreForm::updateOffre($offre, $distanciel);
        }

        return $this->render('/offres/create');
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
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
                $stmt = Database::get_conn()->prepare("INSERT INTO `Postuler`(`idoffre`, `idUtilisateur`, `dates`) VALUES (?,?" . date('d-m-Y') . " )");
                $stmt->execute([$id, Application::getUser()->id()]);
                Application::$app->response->redirect('/offres');
            }
        }
        return $this->render('candidature/postuler', [
            'form' => $form
        ]);
    }
}