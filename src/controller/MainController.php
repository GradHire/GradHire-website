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
        $filter = self::constructFilter();
        print_r($filter);
        if (empty($search) && empty($filter)) {
            $offres = (new OffresRepository())->recuperer();
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }
        $offres = (new OffresRepository())->search($search, $filter);
        if ($offres == null) {
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }
        return $this->render('offres/listOffres', ['offres' => $offres]);
    }

    private static function constructFilter():array {
        $filter = array();
        if (isset($_GET['thematique'])) {
            $filter['thematique'] = "";
            foreach ($_GET['thematique'] as $key => $value) {
                if ($filter['thematique'] == null) {
                    $filter['thematique'] = $value;
                } else if ($filter['thematique'] != null){
                    $filter['thematique'] .= ','. $value;
                }
            }
        }
        if (isset($_GET['anneeVisee'])) {
            $filter['anneeVisee'] = $_GET['anneeVisee'];
        }
        if (isset($_GET['duree'])) {
            $filter['duree'] = $_GET['duree'];
        }
        if (isset($_GET['alternance'])) {
            $filter['alternance'] = $_GET['alternance'];
        }
        if (isset($_GET['stage'])) {
            $filter['stage'] = $_GET['stage'];
        }
        if (isset($_GET['gratificationMin'])) {
            if ($_GET['gratificationMin']==""){
                $filter['gratificationMin'] = null;
            }
            elseif ($_GET['gratificationMin'] < 4.05) {
                $filter['gratificationMin'] = 4.05;
            } else if ($_GET['gratificationMin'] > 15){
                $filter['gratificationMin'] = 15;
            } else {
                $filter['gratificationMin'] = $_GET['gratificationMin'];
            }
        }
        if (isset($_GET['gratificationMax'])) {
            if ($_GET['gratificationMax']==""){
                $filter['gratificationMax'] = null;
            }
            else if ($_GET['gratificationMax'] < 4.05) {
                $filter['gratificationMax'] = 4.05;
            } else if ($_GET['gratificationMax'] > 15){
                $filter['gratificationMax'] = 15;
            } else {
                $filter['gratificationMax'] = $_GET['gratificationMax'];
            }
        }
        return $filter;
    }
}
