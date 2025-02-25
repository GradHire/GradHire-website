<?php

namespace app\src\controller;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\LdapRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\repository\ProRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\Request;
use app\src\model\Response;
use app\src\view\components\ui\Notification;
use Exception;

class AuthController extends AbstractController
{
    /**
     * @throws ServerErrorException
     */
    public function pro_login(Request $request): string|null
    {
        try {
            if (!Application::isGuest()) header("Location: /");
            $loginForm = new FormModel([
                "email" => FormModel::email("Adresse mail")->required()->asterisk(),
                "password" => FormModel::password("Mot de passe")->min(8)->asterisk(),
                "remember" => FormModel::switch("Rester connecté")->default(true)->forget()
            ]);
            if ($request->getMethod() === 'post') {
                if ($loginForm->validate($request->getBody())) {
                    $dt = $loginForm->getParsedBody();
                    if (ProRepository::login($dt["email"], $dt["password"], $dt["remember"], $loginForm)) {
                        Application::$app->response->redirect('/');
                        return null;
                    }
                }
            }
            return $this->render('auth/pro_login', [
                'form' => $loginForm
            ]);
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function login(Request $request): string|null
    {
        if (!Application::isGuest()) header("Location: /");
        $loginForm = new FormModel([
            "username" => FormModel::string("Login ldap")->required()->asterisk(),
            "password" => FormModel::password("Mot de passe")->required()->asterisk(),
            "remember" => FormModel::switch("Rester connecté")->default(true)
        ]);
        if ($request->getMethod() === 'post') {
            if ($loginForm->validate($request->getBody())) {
                $dt = $loginForm->getParsedBody();
                if (LdapRepository::login($dt["username"], $dt["password"], $dt["remember"], $loginForm)) {
                    Application::redirectFromParam('/');
                    return null;
                }
            }
        }
        return $this->render('auth/login', [
            'form' => $loginForm
        ]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function resetPassword(Request $req): string
    {
        if (!Application::isGuest()) throw new ForbiddenException();
        $form = new FormModel([
            "email" => FormModel::email("Email")->required()->asterisk(),
        ]);
        if ($req->getMethod() == "post") {
            if ($form->validate($req->getBody())) {
                $body = $form->getParsedBody();
                ProRepository::forgetPassword($body["email"]);
                $form->setSuccess("Un email vous à été envoyé pour réinitialiser votre mot de passe");
            }
        }
        return $this->render('resetPassword/form', [
            'form' => $form
        ]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function forgetPassword(Request $req, Response $res): string
    {
        if (!Application::isGuest()) throw new ForbiddenException();
        $token = $req->getRouteParam("token");
        $infos = ProRepository::checkForgetToken($token);
        if (!$infos) throw new NotFoundException();
        $form = new FormModel([
            "password" => FormModel::password("Nouveau mot de passe")->min(8)->required()->asterisk(),
            "password2" => FormModel::password("Répéter mot de passe")->match("password")->min(8)->required()->asterisk()
        ]);
        if ($req->getMethod() == "post") {
            if ($form->validate($req->getBody())) {
                $body = $form->getParsedBody();
                ProRepository::setNewPassword($body["password"], $infos["idutilisateur"]);
                ProRepository::deleteForgetToken($infos["idutilisateur"]);
                Notification::createNotification("Mot de passe modifié !");
                NotificationRepository::createNotification($infos["idutilisateur"], "Votre mot de passe a été modifié avec succès", "");
                $res->redirect('/pro_login');
            }
        }
        return $this->render('resetPassword/forgetPassword', [
            'form' => $form
        ]);
    }

    public function logout(Request $request, Response $response): void
    {
        Auth::logout();
        $response->redirect('/');
    }

    /**
     * @throws ServerErrorException
     */
    public function createTutor(Request $req): string
    {
        try {
            $token = $req->getRouteParam('token');
            $sql = "SELECT c.tokenCreation, c.email, u.nom, u.idUtilisateur AS idEntreprise FROM CreationCompteTuteur c LEFT JOIN Utilisateur u ON u.idUtilisateur = c.idUtilisateur WHERE tokenCreation = ?";
            $statement = Database::get_conn()->prepare($sql);
            $statement->execute([$token]);
            $count = $statement->rowCount();
            if ($count === 0) Application::$app->response->redirect('/');
            $data = $statement->fetch();

            $form = new FormModel([
                "name" => FormModel::string("Prénom")->required()->asterisk(),
                "surname" => FormModel::string("Nom")->required()->asterisk(),
                "phone" => FormModel::phone("Téléphone")->required()->asterisk(),
                "password" => FormModel::password("Mot de passe")->min(8)->asterisk(),
                "password2" => FormModel::password("Répéter mot de passe")->match('password')->asterisk(),
            ]);

            if ($req->getMethod() === 'post') {
                if ($form->validate($req->getBody())) {
                    if (TuteurEntrepriseRepository::register($form->getParsedBody(), $data, $form)) {
                        NotificationRepository::createNotification($data['idEntreprise'], "Votre tuteur a été créé avec succès", "/ListeTuteurPro");
                        Application::$app->response->redirect('/');
                        return '';
                    }
                }
            }

            return $this->render('auth/register_tutor', [
                'enterprise' => $data['nom'],
                'form' => $form
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function register(Request $request): string
    {
        if (!Application::isGuest()) header("Location: /");
        $form = new FormModel([
            "name" => FormModel::string("Nom entreprise")->required()->min(1)->asterisk(),
            "email" => FormModel::email("Adresse mail")->required()->asterisk(),
            "siret" => FormModel::string("Siret")->required()->numeric()->asterisk()->length(14),
            "phone" => FormModel::phone("Téléphone")->required()->asterisk(),
            "password" => FormModel::password("Mot de passe")->min(8)->asterisk(),
            "password2" => FormModel::password("Répéter mot de passe")->match('password')->asterisk(),
        ]);
        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $dt = $form->getParsedBody();
                if (EntrepriseRepository::register($dt, $form)) {
                    Application::$app->response->redirect('/');
                    return '';
                }
            }
        }
        return $this->render('auth/register', [
            'form' => $form
        ]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function modifierMdp(Request $req): string
    {
        if (Application::isGuest() || !Auth::has_role(Roles::Enterprise, Roles::Tutor)) throw new ForbiddenException();
        $form = new FormModel([
            "actual" => FormModel::password("Mot de passe actuel")->required()->asterisk(),
            "password" => FormModel::password("Nouveau mot de passe")->min(8)->asterisk()->required(),
            "password2" => FormModel::password("Répéter mot de passe")->match('password')->asterisk()->required(),
        ]);
        if ($req->getMethod() === 'post') {
            if ($form->validate($req->getBody())) {
                $dt = $form->getParsedBody();
                if (ProRepository::changePassword($dt["actual"], $dt["password"], $form)) {
                    Notification::createNotification("Votre mot de passe a été modifié avec succès", "success");
                    Application::$app->response->redirect('/profile');
                    return '';
                }
            }
        }
        return $this->render('profile/passwordChange', [
            'form' => $form
        ]);
    }

    public function home(): string
    {
        if (Application::isGuest()) return $this->render('info-pages/home');
        if (Auth::has_role(Roles::ManagerAlternance, Roles::ManagerStage, Roles::ChefDepartment, Roles::Manager, Roles::Staff, Roles::Teacher)) Application::$app->response->redirect('/dashboard');
        else Application::$app->response->redirect('/offres');
        return '';
    }

    public function aboutreal(): string
    {
        return $this->render('info-pages/apropos');
    }

    public function conditionutilisation(): string
    {
        return $this->render('info-pages/conditionutilisation');
    }

    public function politiqueconfidentialite(): string
    {
        return $this->render('info-pages/politiqueconfidentialite');
    }
}
