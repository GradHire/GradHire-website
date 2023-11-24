<?php

namespace app\src\controller;

use app\src\core\components\Notification;
use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\LdapRepository;
use app\src\model\repository\ProRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\Request;
use app\src\model\Response;

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
                "email" => FormModel::email("Adresse mail")->required(),
                "password" => FormModel::password("Mot de passe")->min(8),
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
            return $this->render('pro_login', [
                'form' => $loginForm
            ]);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function login(Request $request): string|null
    {
        try {

            if (!Application::isGuest()) header("Location: /");
            $loginForm = new FormModel([
                "username" => FormModel::string("Login ldap")->required(),
                "password" => FormModel::password("Mot de passe")->required(),
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
            return $this->render('login', [
                'form' => $loginForm
            ]);
        } catch (\Exception $e) {
            print_r($e);
        }
        return '';
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
                        Application::$app->response->redirect('/');
                        return '';
                    }
                }
            }

            return $this->render('register_tutor', [
                'enterprise' => $data['nom'],
                'form' => $form
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException();
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
        return $this->render('register', [
            'form' => $form
        ]);
    }

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
        return $this->render('passwordChange', [
            'form' => $form
        ]);
    }
}