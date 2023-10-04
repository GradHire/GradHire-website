<?php

namespace app\src\controller;

use app\src\core\middlewares\AuthMiddleware;
use app\src\model\Application;
use app\src\model\LoginForm;
use app\src\model\Repository\OffresRepository;
use app\src\model\Request;
use app\src\model\Response;
use app\src\model\User;

class MainController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function home(): string
    {
        return $this->render('home', [
            'name' => 'GradHire'
        ]);
    }

    public function login(Request $request): string
    {
        $loginForm = new LoginForm();
        if ($request->getMethod() === 'post') {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                Application::$app->response->redirect('/');
                return '';
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function register(Request $request): string
    {
        $registerModel = new User();
        if ($request->getMethod() === 'post') {
            $registerModel->loadData($request->getBody());
            if ($registerModel->validate() && $registerModel->save()) {
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
                return 'Show success page';
            }

        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }

    public function logout(Request $request, Response $response): void
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function contact(): string
    {
        return $this->render('contact');
    }

    public function profile(): string
    {
        return $this->render('profile');
    }

    public function offres(): string
    {
        $search = $_GET['search'] ?? "";
        $filter = array();
        if (isset($_GET['anneeVisee'])) {
            $filter['anneeVisee'] = $_GET['anneeVisee'];
        }
//        echo $_GET['thematique'];
//        if (isset($_GET['thematique'])) {
//            $filter['thematique'] = $_GET['thematique'];
//        }
        if (empty($search) && !self::checkFilterEmpty($filter)) {
            $offres = (new OffresRepository())->recuperer();
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }
        $offres = (new OffresRepository())->search($search, $filter);
        if ($offres == null) {
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }
        return $this->render('offres/listOffres', ['offres' => $offres]);
    }

    private static function checkFilterEmpty(array $filter): bool
    {
        foreach ($filter as $key => $value) {
            if ($value == null) {
                return false;
            }
        }
        return true;
    }
}
