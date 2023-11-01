<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\LdapRepository;
use app\src\model\repository\ProRepository;
use app\src\model\Request;
use app\src\model\Response;

class AuthController extends AbstractController
{
    /**
     * @throws ServerErrorException
     */
    public function register(Request $request): string
    {
        if (!Application::isGuest()) header("Location: /");
        $form = new FormModel([
            "name" => FormModel::string("Nom entreprise")->required()->min(10)->asterisk(),
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

    /**
     * @throws ServerErrorException
     */
    public function pro_login(Request $request): string|null
    {
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
    }

    /**
     * @throws ServerErrorException
     */
    public function login(Request $request): string|null
    {
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
    }

    public function logout(Request $request, Response $response): void
    {
        Auth::logout();
        $response->redirect('/');
    }
}