<?php

namespace app\src\controller;

use app\src\model\Application;
use app\src\model\Auth\LdapAuth;
use app\src\model\Request;
use app\src\model\Response;

class AuthController extends Controller
{
    public function login(Request $request): string|null
    {
        $loginForm = new LdapAuth();
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

    public function register(Request $request): string
    {
        /*$registerModel = new User();
        if ($request->getMethod() === 'post') {
            $registerModel->loadData($request->getBody());
            if ($registerModel->validate() && $registerModel->save()) {
                //Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
                return 'Show success page';
            }
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);*/
        return '';
    }

    public function logout(Request $request, Response $response): void
    {
        unset($_COOKIE["token"]);
        setcookie('token', '', -1);
        session_destroy();
        $response->redirect('/');
    }
}