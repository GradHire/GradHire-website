<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormBool;
use app\src\model\Form\FormModel;
use app\src\model\Form\FormString;
use app\src\model\Request;
use app\src\model\Response;
use app\src\model\Users\EnterpriseUser;
use app\src\model\Users\LdapUser;
use app\src\model\Users\ProUser;

class AuthController extends Controller
{
	/**
	 * @throws ServerErrorException
	 */
	public function register(Request $request): string
	{
		$form = new FormModel([
			"name" => FormString::New("Nom entreprise")->required(),
			"email" => FormString::New("Adresse mail")->email()->required(),
			"siret" => FormString::New("Siret")->required()->numeric(),
			"phone" => FormString::New("Téléphone")->required()->numeric(),
			"password" => FormString::New("Mot de passe")->password()->min(8),
			"password2" => FormString::New("Répéter mot de passe")->password()->match('password'),

		]);
		if ($request->getMethod() === 'post') {
			if ($form->validate($request->getBody())) {
				$dt = $form->get_data();
				if (EnterpriseUser::register($dt, $form)) {
					return 'Compte créer vous recevrez un mail lorsque votre compte sera validé';
				}
			}
		}
		$this->setLayout('auth');
		return $this->render('register', [
			'form' => $form
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function pro_login(Request $request): string|null
	{
		$loginForm = new FormModel([
			"email" => FormString::New("Adresse mail")->email()->required(),
			"password" => FormString::New("Mot de passe")->password()->min(8),
			"remember" => FormBool::New("Rester connecté")
		]);
		if ($request->getMethod() === 'post') {
			if ($loginForm->validate($request->getBody())) {
				$dt = $loginForm->get_data();
				if (ProUser::login($dt["email"], $dt["password"], $dt["remember"], $loginForm)) {
					Application::$app->response->redirect('/');
					return null;
				}
			}
		}
		$this->setLayout('auth');
		return $this->render('pro_login', [
			'form' => $loginForm
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function login(Request $request): string|null
	{
		$loginForm = new FormModel([
			"username" => FormString::New("Login ldap")->min(1),
			"password" => FormString::New("Mot de passe")->password()->min(1),
			"remember" => FormBool::New("Rester connecté")
		]);
		if ($request->getMethod() === 'post') {
			if ($loginForm->validate($request->getBody())) {
				$dt = $loginForm->get_data();
				if (LdapUser::login($dt["username"], $dt["password"], $dt["remember"], $loginForm)) {
					Application::$app->response->redirect('/');
					return null;
				}
			}
		}
		$this->setLayout('auth');
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