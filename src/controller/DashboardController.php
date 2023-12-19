<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\CompteRenduRepository;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\DashboardRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\SuperviseRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\repository\VisiteRepository;
use app\src\model\Request;
use app\src\view\components\calendar\Event;
use app\src\view\components\ui\Notification;
use DateTime;

class DashboardController extends AbstractController
{

    public function modifierParametres(Request $request): void
    {
        $dashboardModel = new DashboardRepository();
        $dashboardModel->modifyParams($request);
    }

    public function showDashboard(): string
    {
        $dashboardModel = new DashboardRepository();
        $data = $dashboardModel->fetchDashboardData();

        return $this->render('dashboard/dashboard', [
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
        } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance)) {
            $utilisateur = [];
            $entreprises = (new EntrepriseRepository([]))->getAll();
            $etudiants = (new EtudiantRepository([]))->getAll();
            $tuteurs = (new TuteurRepository([]))->getAll();
            $staffs = (new StaffRepository([]))->getAll();
            $utilisateur = array_merge($utilisateur, $entreprises, $etudiants, $tuteurs, $staffs);
        } else {
            throw new ForbiddenException("Vous n'avez pas le droit de voir les utilisateurs");
        }

        return $this->render('utilisateurs/utilisateurs', ['utilisateurs' => $utilisateur]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function role(Request $req): void
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
                (new MailRepository())->send_mail([$user->getEmail()], "Archivage de votre compte", "Votre compte a été archivé");
            } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance)) {
                (new UtilisateurRepository([]))->setUserToArchived($user, false);
                (new MailRepository())->send_mail([$user->getEmail()], "Désarchivage de votre compte", "Votre compte a été désarchivé");
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
            $visite = VisiteRepository::getByStudentId(Application::getUser()->id());
            if (!$visite)
                $visites[] = $visite;
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
        } else if (Auth::has_role(Roles::Enterprise)) {
            $visites = VisiteRepository::getAllByEnterpriseId(Application::getUser()->id());
            $soutenances = [];
        } else
            throw new ForbiddenException("Vous n'avez pas le droit de voir le calendrier");

        foreach ($visites as $visite) {
            if ($visite == null) continue;
            $title = "Visite de stage";
            if (!Auth::has_role(Roles::Student)) {
                $supervise = SuperviseRepository::getByConvention($visite->getNumConvention());
                $name = EtudiantRepository::getFullNameByID($supervise->getIdetudiant());
                $title .= " de " . $name;
            }
            $e = new Event($title, $visite->getDebutVisite(), $visite->getFinVisite(), "#1c4ed8");
            if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage) || (Auth::has_role(Roles::Student) && ConventionRepository::getStudentId($visite->getNumConvention()))) $e->setButton("Voir plus", "/visite/" . $visite->getNumConvention());
            if ($visite->getFinVisite() < new DateTime('now') && ConventionRepository::imOneOfTheTutor(Auth::get_user()->id(), $visite->getNumConvention())) {
                if (!CompteRenduRepository::checkIfCompteRenduProfExist($visite->getNumConvention()) && Auth::has_role(Roles::TutorTeacher))
                    $e->setButton("deposer compte rendu prof", "/compteRendu/" . $visite->getNumConvention());
                else if (!CompteRenduRepository::checkIfCompteRenduEntrepriseExist($visite->getNumConvention()) && Auth::has_role(Roles::Tutor))
                    $e->setButton("deposer compte rendu entreprise", "/compteRendu/" . $visite->getNumConvention());
            }
            $events[] = $e;
        }
        foreach ($soutenances as $soutenance) {
            if ($soutenance == null) continue;
            $title = "Soutenance";
            if (!Auth::has_role(Roles::Student)) {
                $userId = ConventionRepository::getStudentId($soutenance->getNumConvention());
                $name = EtudiantRepository::getFullNameByID($userId);
                $title .= " de " . $name;
            }
            $e = new Event($title, $soutenance->getDebutSoutenance(), $soutenance->getFinSoutenance(), "#1c4ed8");
            if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage)) $e->setButton("Voir plus", "/voirSoutenance/" . $soutenance->getNumConvention());
            if (Auth::has_role(Roles::Student) && Auth::get_user()->id() == ConventionRepository::getStudentId($soutenance->getNumConvention())) $e->setButton("Voir plus", "/voirSoutenance/" . $soutenance->getNumConvention());
            if ($soutenance->getFinSoutenance() < new DateTime('now') && ConventionRepository::getIfIdTuteurs($soutenance->getNumConvention(), Application::getUser()->id())) {
                if (!CompteRenduRepository::checkIfCompteRenduSoutenanceProfExist($soutenance->getNumConvention()) && Auth::has_role(Roles::TutorTeacher))
                    $e->setButton("deposer compte rendu soutenance prof", "/compteRenduSoutenance/" . $soutenance->getNumConvention());
                else if (!CompteRenduRepository::checkIfCompteRenduSoutenanceEntrepriseExist($soutenance->getNumConvention()) && Auth::has_role(Roles::Tutor))
                    $e->setButton("deposer compte rendu soutenance entreprise", "/compteRenduSoutenance/" . $soutenance->getNumConvention());
            }
            $events[] = $e;
        }
        return $this->render('calendar', ['events' => $events]);
    }
}
