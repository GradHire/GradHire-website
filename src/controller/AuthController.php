<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth\EnterpriseRegister;
use app\src\model\Auth\LdapLogin;
use app\src\model\Auth\ProLogin;
use app\src\model\Request;
use app\src\model\Response;

class AuthController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function register(Request $request): string
    {
        $registerModel = new EnterpriseRegister();
        if ($request->getMethod() === 'post') {
            $registerModel->loadData($request->getBody());
            if ($registerModel->validate() && $registerModel->register()) {
                return 'Compte créer vous recevrez un mail lorsque votre compte sera validé';
            }

        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function pro_login(Request $request): string|null
    {
        $loginForm = new ProLogin();
        if ($request->getMethod() === 'post') {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                Application::$app->response->redirect('/');
                return null;
            }
        }
        $this->setLayout('auth');
        return $this->render('pro_login', [
            'model' => $loginForm
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function login(Request $request): string|null
    {
        $loginForm = new LdapLogin();
        if ($request->getMethod() === 'post') {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                Application::$app->response->redirect('/');
                return null;
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function logout(Request $request, Response $response): void
    {
        unset($_COOKIE["token"]);
        setcookie('token', '', -1);
        session_destroy();
        $response->redirect('/');
    }
}