<?php

namespace app\src\controller;

use app\src\core\components\Notification;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\Request;

class DashboardController extends AbstractController
{
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
}