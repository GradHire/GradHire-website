<?php

namespace app\src\controller;

use app\src\core\components\Calendar\Event;
use app\src\core\components\Notification;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\repository\VisiteRepository;
use app\src\model\Request;

class DashboardController extends AbstractController
{

    /**
     * @throws ServerErrorException
     */
    public function showDashboard(): string
    {
        $currentTab = $_COOKIE['currentTab'] ?? 'tab1';

        $data = [];

        if ($currentTab === 'tab1') {
            $data['percentageBlockData1'] = (new ConventionRepository())->getPourcentageEtudiantsConventionCetteAnnee();
            $data['numBlockData1'] = (new OffresRepository())->getStatsDensembleStageEtAlternance();
            $data['barChartHorizontalData1'] = (new OffresRepository())->getTop5DomainesPlusDemandes();
            $data['pieChartData1'] = (new OffresRepository())->getStatsDistributionDomaine();
            $data['barChartVerticalData1'] = (new OffresRepository())->getMoyenneCandidaturesParOffreParDomaine();
            $data['lineChartData1'] = (new PostulerRepository())->getStatsCandidaturesParMois();
            $data['lastActionsData1'] = (new OffresRepository())->getOffresDernierSemaine();
        } elseif ($currentTab === 'tab2') {
            $data = [];
        } elseif ($currentTab === 'tab3') {
            $data = [];
        }
        return $this->render('dashboard/dashboard', [
            'currentTab' => $currentTab,
            'data' => $data
        ]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function utilisateurs(Request $request): string
    {
        if (Auth::has_role(Roles::Student)) Application::redirectFromParam('/profile');
        $id = $request->getRouteParams()['id'] ?? null;
        if (!is_null($id) && !Auth::has_role(Roles::Manager, Roles::Staff)) throw new ForbiddenException();
        if ($id != null) {
            if ((new EntrepriseRepository([]))->getByIdFull($id) != null) {
                $utilisateur = (new EntrepriseRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailEntreprise', ['utilisateur' => $utilisateur]);
            } elseif ((new EtudiantRepository([]))->getByIdFull($id) != null) {
                $utilisateur = (new EtudiantRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailEtudiant', ['utilisateur' => $utilisateur]);
            } elseif ((new TuteurRepository([]))->getByIdFull($id) != null) {
                $utilisateur = (new TuteurRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailTuteur', ['utilisateur' => $utilisateur]);
            } elseif ((new StaffRepository([]))->getByIdFull($id) != null) {
                $utilisateur = (new StaffRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailStaff', ['utilisateur' => $utilisateur]);
            }
        }
        if (Auth::has_role(Roles::ChefDepartment)) {
            $utilisateur = (new StaffRepository([]))->getAll();
            $utilisateur = array_filter($utilisateur, function ($user) {
                return $user->getRole() !== Roles::ChefDepartment->value;
            });
        } else
            $utilisateur = (new UtilisateurRepository([]))->getAll();

        return $this->render('utilisateurs/utilisateurs', ['utilisateurs' => $utilisateur]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function role(Request $req)
    {
        if ($req->getMethod() !== 'post')
            throw new ForbiddenException();
        if (!Auth::has_role(Roles::ChefDepartment)) throw new ForbiddenException();
        if (!isset($req->getBody()['role']))
            throw new ForbiddenException();
        $role = $req->getBody()['role'];
        $id = $req->getRouteParams()['id'];
        StaffRepository::updateRole($id, $role);
        Notification::createNotification("Le rôle de l'utilisateur a été modifié");
        Application::redirectFromParam('/utilisateurs');
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function ListeTuteurPro(Request $request): string
    {
        $form = new FormModel([
            'email' => FormModel::email("Email tuteur")->required()->asterisk()->forget()
        ]);
        if (Auth::has_role(Roles::Manager, Roles::Staff)) $tuteurs = (new TuteurEntrepriseRepository([]))->getAll();
        else if (Auth::has_role(Roles::Enterprise)) $tuteurs = (new TuteurEntrepriseRepository([]))->getAllTuteursByIdEntreprise(Application::getUser()->id());
        else throw new ForbiddenException();
        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                TuteurEntrepriseRepository::generateAccountToken(Application::getUser(), $form->getParsedBody()["email"], $form);
            }
        }
        $waiting = EntrepriseRepository::getTuteurWaitingList(Application::getUser()->id());
        return $this->render('tuteurPro/listeTuteurPro', ['tuteurs' => $tuteurs, 'form' => $form, 'waiting' => $waiting]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     * @throws NotFoundException
     */


    /**
     * @throws ServerErrorException
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function archiver(Request $req): string
    {
        if (Application::isGuest()) throw new ForbiddenException();
        $id = $req->getRouteParams()["id"];
        if (Auth::has_role(Roles::Enterprise) && (string)Application::getUser()->id() !== $id) {
            $exist = (new TuteurEntrepriseRepository([]))->getById($id);
            if (!$exist || $exist->getIdentreprise() !== Application::getUser()->id()) throw new ForbiddenException();
        }
        if ((string)Application::getUser()->id() === $id ||
            Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance, Roles::Enterprise)
        ) {
            $user = (new UtilisateurRepository([]))->getUserById($req->getRouteParams()["id"]);
            if ($user == null) throw new NotFoundException();
            if (!(new UtilisateurRepository([]))->isArchived($user)) {
                (new UtilisateurRepository([]))->setUserToArchived($user, true);
                (new MailRepository())->send_mail([$user->getEmailutilisateur()], "Archivage de votre compte", "Votre compte a été archivé");
            } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance)) {
                (new UtilisateurRepository([]))->setUserToArchived($user, false);
                (new MailRepository())->send_mail([$user->getEmailutilisateur()], "Désarchivage de votre compte", "Votre compte a été désarchivé");
            }
            if ((string)Application::getUser()->id() === $id)
                Auth::logout();
            Application::redirectFromParam('/');
            return '';
        } else throw new ForbiddenException();
    }

    /**
     * @throws ServerErrorException
     * @throws ForbiddenException
     */
    public function calendar(): string
    {
        $events = [];
        $visites = [];
        if (Auth::has_role(Roles::Student)) {
            $visites = VisiteRepository::getAllByStudentId(Application::getUser()->id());
            $soutenances = SoutenanceRepository::getAllSoutenancesByIdEtudiant(Application::getUser()->id());
        } else if (Auth::has_role(Roles::Tutor)) {
            $visites = VisiteRepository::getAllByEnterpriseTutorId(Application::getUser()->id());
            $soutenances = SoutenanceRepository::getAllSoutenancesByIdTuteurEntreprise(Application::getUser()->id());
        } else if (Auth::has_role(Roles::TutorTeacher)) {
            $visites = VisiteRepository::getAllByUniversityTutorId(Application::getUser()->id());
            $soutenances = SoutenanceRepository::getAllSoutenancesByIdTuteurProf(Application::getUser()->id());
        } else if (Auth::has_role(Roles::Teacher, Roles::Manager, Roles::ManagerStage, Roles::ManagerAlternance)) {
            $soutenances = SoutenanceRepository::getAllSoutenances();
            $visites = VisiteRepository::getAllVisites();
        } else
            throw new ForbiddenException();

        foreach ($visites as $visite) {
            $title = "Visite de stage";
            if (!Auth::has_role(Roles::Student)) {
                $name = EtudiantRepository::getFullNameByID($visite->getIdEtudiant());
                $title .= " de " . $name;
            }
            $e = new Event($title, $visite->getDebutVisite(), $visite->getFinVisite(), "#1c4ed8");
            if (Auth::has_role(Roles::TutorTeacher))
                $e->setButton("Voir plus", "/feur");
            $events[] = $e;
        }
        foreach ($soutenances as $soutenance) {
            $title = "Soutenance";
            if (!Auth::has_role(Roles::Student)) {
                $userId = ConventionRepository::getStudentId($soutenance->getNumConvention());
                $name = EtudiantRepository::getFullNameByID($userId);
                $title .= " de " . $name;
            }
            $e = new Event($title, $soutenance->getDebutSoutenance(), $soutenance->getFinSoutenance(), "#1c4ed8");
            $e->setButton("Voir plus", "/conventions/" . $soutenance->getNumConvention());
            $events[] = $e;
        }
        return $this->render('calendar', ['events' => $events]);
    }
}