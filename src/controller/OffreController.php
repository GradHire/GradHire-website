<?php

namespace app\src\controller;

use app\src\core\components\Notification;
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
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
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
        if (Application::isGuest() || Auth::has_role(Roles::ChefDepartment)) throw new ForbiddenException();
        if (Auth::has_role(Roles::Enterprise, Roles::Tutor))
            return $this->render('entreprise/offres', ['offres' => OffresRepository::getAllByEnterprise()]);
        $form = new FormModel([
            "type" => FormModel::checkbox("", ["stage" => "Stage", "alternance" => "Alternance"])->horizontal()->border(),
            "year" => FormModel::select("Année visée", ["all" => "Toutes", "2" => "BUT2", "3" => "BUT3"])->sm()->border()->default($_GET['year'] ?? "all"),
            "duration" => FormModel::select("Durée", ["all" => "Toutes", "1" => "1 ans", "1.5" => "1 ans et demi", "2" => "2 ans"])->sm()->border()->default($_GET['duration'] ?? "all"),
            "theme" => FormModel::checkbox("Thématique", ["Réseaux" => "Réseaux", "secu" => "Securite", "BDD" => "Base de Donnée", "DevWeb" => "Développement Web", "DevApp" => "Développement d'application"])->sm()->border()->default($_GET['theme'] ?? []),
            "gratification" => FormModel::range("Gratification", 4.05, 15)->sm()->default([4.05, 15]),
        ]);
        $form->setMethod("get");
        if (!$form->validate($_GET)) header("Location: /offres");
        $offres = OffresRepository::getAllWithFilter($form->getParsedBody());
        return $this->render('offres/listOffres', ['offres' => $offres, "form" => $form]);
    }

    public function detailOffre(Request $request)
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->getByIdWithUser($id);
        if ($offre == null && $id != null) throw new NotFoundException();
        return $this->render('offres/detailOffre', ['offre' => $offre]);
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
                (new OffresRepository())->updateToArchiver($id);
                (new MailRepository())->send_mail([(new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Archivage de votre offre", "Votre offre a été archivée");
                $offre = (new OffresRepository())->getByIdWithUser($id);
                header("Location: /offres/" . $id);
            } elseif ($request->getMethod() === 'get') {
                (new OffresRepository())->updateToArchiver($id);
                (new MailRepository())->send_mail([(new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur())->getEmailutilisateur()], "Archivage de votre offre", "Votre offre a été archivée");
                Application::redirectFromParam("/offres");
            }
            return $this->render('offres/detailOffre', ['offre' => $offre]);
        }
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function creeroffre(Request $request): string
    {
        if (!Auth::has_role(Roles::Manager, Roles::Enterprise, Roles::Staff))
            throw new ForbiddenException();

        $id = $request->getRouteParams()['id'] ?? null;
        if ($id !== null && !Auth::has_role(Roles::Enterprise)) throw new NotFoundException();

        $draft = $id !== null ? (new OffresRepository())->getById($id) : null;
        if ($id !== null && $draft === null) throw new NotFoundException();
        if ($id !== null && $draft->getIdutilisateur() !== Application::getUser()->id()) throw new ForbiddenException();

        $offres = (new OffresRepository)->draftExist(Application::getUser()->id()) ?? [];
        $options = ["new" => "Nouveau brouillon"];
        foreach ($offres as $offre)
            $options[$offre->getIdoffre()] = $offre->getSujet();

        $brouillon = new FormModel([
            "draft" => FormModel::select("Choisir un brouillon", $options)->id("draftSelect")->default($id !== null ? $id : "new")
        ]);
        $form = new FormModel([
            "typeStage" => FormModel::checkbox("Type de stage", ["stage" => "Stage", "alternance" => "Alternance"])->horizontal(),
            "sujet" => FormModel::string("Sujet")->required()->default($draft ? $draft->getSujet() : ""),
            "theme" => FormModel::select("Thématique", ["Réseaux" => "Réseaux", "secu" => "Securite", "bdd" => "Base de Donnée", "DevWeb" => "Développement Web", "DevApp" => "Développement d'application"])->required()->default($draft ? $draft->getThematique() : ""),
            "nbjourtravailhebdo" => FormModel::int("Nombre de jours de Travail")->min(1)->max(6)->required()->default($draft ? $draft->getNbjourtravailhebdo() : 5),
            "nbheureparjour" => FormModel::double("Nombre d'heure par jour")->default($draft ? $draft->getNbJourTravailHebdo() : 7)->min(1)->max(12)->required(),
            "gratification" => FormModel::double("Tarif horaire")->min(4.05)->max(15)->required()->default($draft ? $draft->getGratification() : 4.05),
            "avantage" => FormModel::string("Avantages")->default($draft ? $draft->getAvantageNature() : ""),
            "datedebut" => FormModel::date("Date de début")->id("dateDebut")->after(new \DateTime())->required()->default($draft ? $draft->getDateDebut() : ""),
            "datefin" => FormModel::date("Date de fin")->id("dateFin")->after(new \DateTime())->required()->default($draft ? $draft->getDateFin() : ""),
            "distanciel" => FormModel::int("Distanciel")->min(0)->max(6)->default(0)->id("distanciel"),
            "dureeStage" => FormModel::int("Durée du stage (en heure)")->id("dureeStage")->min(1)->default(1),
            "dureeAlternance" => FormModel::int("Durée de l'alternance")->id("dureeAlternance")->min(1)->default(1),
            "description" => FormModel::string("Description")->textarea()->required()->default($draft ? $draft->getDescription() : ""),
        ]);

        if ($request->getMethod() === 'post') {
            $action = $_POST['action'];
            if ($action === "delete" && $id !== null && Auth::has_role(Roles::Enterprise)) {
                OffreForm::deleteOffre($id);
                Notification::createNotification("Brouillon supprimé");
                header("Location: /offres/create");
            }
            print_r($request->getBody());
            if ($form->validate($request->getBody())) {
                $body = $form->getParsedBody();
                $idUtilisateur = Auth::has_role(Roles::Enterprise) ? Application::getUser()->id() : $_POST['identreprise'];
                $o = new Offre($id, $body["duree"], $body["theme"], $body["sujet"], $body["nbjourtravailhebdo"], $body["nbheureparjour"], $body["gratification"], $body["avantage"], $body["datedebut"], $body["datefin"], "brouillon", 0, $body["anneeVisee"], $body["annee"], $idUtilisateur, date("Y-m-d H:i:s"), $body["description"]);
                
                if ($action === "save" && Auth::has_role(Roles::Enterprise)) {
                    /*if ($id !== null) {
                        OffreForm::updateOffre($o);
                        Notification::createNotification("Brouillon sauvegardé");
                        header("Location: /offres/create");
                    } else {
                        OffreForm::creerOffre($o);
                        Notification::createNotification("Brouillon sauvegardé");
                        header("Location: /offres/create");
                    }*/
                } elseif ($action === "send") {

                } else {
                    throw new ForbiddenException();
                }
            }
        }

        if (!Auth::has_role(Roles::Enterprise)) {
            $entreprises = EntrepriseRepository::getList();
            $options = [];
            foreach ($entreprises as $entreprise) {
                $options[$entreprise["idutilisateur"]] = $entreprise["nom"];
            }
            $enterpriseForm = new FormModel([
                "identreprise" => FormModel::select("Entreprise", $options)->required(),
            ]);
        }

        return $this->render('/offres/create', ['form' => $form, 'draft' => $brouillon, 'offre' => $id !== null, 'enterprises' => $enterpriseForm ?? null]);

        if ($request->getMethod() === 'get') {
            return $this->render('/offres/create');
        }

        $action = $_POST['action'];
        $idOffre = $_POST['id_offre'];
        if ($idOffre === "") {
            $idOffre = null;
        }
        if ($action == 'Supprimer Brouillon') {
            OffreForm::deleteOffre($idOffre);
            return $this->render('/offres/create');
        }

        $typeStage = $_POST['typeStage'] ?? null;
        $typeAlternance = $_POST['typeAlternance'] ?? null;
        $distanciel = $_POST['distanciel'] ?? null;
        if (!empty($_POST['dureeStage']) && !empty($_POST['dureeAlternance'])) {
            $duree = "stage : " . $_POST['dureeStage'] . " heure(s), alternance : " . $_POST['dureeAlternance'] . " an(s)";
        } else if (!empty($_POST["dureeStage"]) && empty($_POST["dureeAlternance"])) {
            $duree = $_POST['dureeStage'] . " heure(s)";
        } else if (empty($_POST["dureeStage"]) && !empty($_POST["dureeAlternance"])) {
            $duree = $_POST['dureeAlternance'] . " an(s)";
        } else {
            $duree = null;
        }

        $theme = $_POST['theme'] ?? null;
        $sujet = $_POST['sujet'];
        $nbJourTravailHebdo = $_POST['nbjourtravailhebdo'] ?? null;
        $nbJourHeureHebdo = $_POST['nbheureparjour'];
        $gratification = $_POST['gratification'];
        $avantageNature = $_POST['avantage'];
        $dateDebut = $_POST['datedebut'] ?? date("Y-m-d H:i:s");
        $dateFin = $_POST['datefin'] ?? date("Y-m-d H:i:s");
        $statut = $action == 'Envoyer' ? "en attente" : "brouillon";
        $pourvue = 0;
        $getDateDebut = explode("-", $dateDebut)[0];
        $getDateFin = explode("-", $dateFin)[0];
        if ($getDateDebut == $getDateFin) {
            $annee = $getDateDebut;
        } else {
            $annee = $getDateDebut . "/" . $getDateFin;
        }
        $anneeVisee = $duree == 1 ? "2" : "3";
        $idUtilisateur = Application::getUser()->role() === Roles::Enterprise ? Application::getUser()->id() : $_POST['identreprise'];
        $datecreation = date("Y-m-d H:i:s");
        $description = $_POST['description'];


        $offre = new Offre($idOffre, $duree, $theme, $sujet, $nbJourTravailHebdo, $nbJourHeureHebdo, $gratification, $avantageNature,
            $dateDebut, $dateFin, $statut, $pourvue, $anneeVisee, $annee, $idUtilisateur, $datecreation, $description);

        if ($idOffre === null) {
            OffreForm::creerOffre($offre, $typeStage, $typeAlternance, $distanciel);
        } else {
            OffreForm::updateOffre($offre, $typeStage, $typeAlternance, $distanciel);
        }

        return $this->render('/offres/create', ['form' => $form]);
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
                $stmt = Database::get_conn()->prepare("INSERT INTO `Postuler`(`idoffre`, `idUtilisateur`, `dates`) VALUES (?,?,?)");
                $values = [
                    $id,
                    Application::getUser()->id(),
                    date("Y-m-d H:i:s")
                ];
                $stmt->execute($values);
                Application::$app->response->redirect('/offres');
            }
        }
        return $this->render('candidature/postuler', [
            'form' => $form
        ]);
    }

    public function mapsOffres(): string
    {
        $offres = (new OffresRepository())->getAll();
        $adresseList = [];
        foreach ($offres as $offre) {
            if (!in_array($offre->getAdresse(), $adresseList)) {
                $adresseList[] = $offre->getAdresse();
            }
        }
        return $this->render('offres/mapOffre', ['adresseList' => $adresseList]);
    }
}