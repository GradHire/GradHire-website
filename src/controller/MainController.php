<?php

namespace app\src\controller;

use app\src\core\middlewares\AuthMiddleware;
use app\src\model\Application;
use app\src\model\LoginForm;
use app\src\model\Offre;
use app\src\model\Request;
use app\src\model\Response;
use app\src\model\User;
use app\src\model\OffreForm;

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

	public function login(Request $request): string|null
	{
		$loginForm = new LoginForm();
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

    public function creeroffre(Request $request): string
    {
        if ($request->getMethod() === 'get') {
            return $this->render('creeroffre');
        }
        else{
            $type=$_POST['radios'];
            $titre=$_POST['titre'];
            $theme=$_POST['theme'];
            $nbjour=$_POST['nbjour'];
            $nbheure=$_POST['nbheure'];
            if($type=="alternance"){
                $distanciel=$_POST['distanciel'];
            }
            else{
                $distanciel=null;
            }
            $salaire=$_POST['salaire'];
            $unitesalaire="heures";
            $statut="en attente";
            $avantage=$_POST['avantage'];
            $dated=$_POST['dated'];
            $datef=$_POST['datef'];
            $duree=$_POST['duree'];
            $description=$_POST['description'];
            $idUtilisateur= 51122324;
            $idOffre=null;
            if($duree==1){
                $anneeVisee="2";
            }
            else{
                $anneeVisee="3";
            }
            //idannee = annee courante
            $idAnnee= date("Y");
            $offre = new Offre($idOffre,$duree,$theme,$titre,$nbjour,$nbheure,$salaire,$unitesalaire,$avantage,$dated,$datef,$statut,$anneeVisee,$idAnnee,$idUtilisateur,$description,$distanciel);
            OffreForm::creerOffre($offre);
            return $this->render('/contact');
        }
    }
}
