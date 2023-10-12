<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
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
			"name" => FormModel::string("Nom entreprise")->required()->min(10),
			"email" => FormModel::email("Adresse mail")->required(),
			"siret" => FormModel::string("Siret")->required()->numeric(),
			"phone" => FormModel::phone("Téléphone")->required(),
			"password" => FormModel::password("Mot de passe")->min(8),
			"password2" => FormModel::password("Répéter mot de passe")->match('password'),

		]);
		if ($request->getMethod() === 'post') {

			if ($form->validate($request->getBody())) {
				$dt = $form->getParsedBody();
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
			"email" => FormModel::email("Adresse mail")->required(),
			"password" => FormModel::password("Mot de passe")->min(8),
			"remember" => FormModel::checkbox("Rester connecté")->default(true)
		]);
		if ($request->getMethod() === 'post') {
			if ($loginForm->validate($request->getBody())) {
				$dt = $loginForm->getParsedBody();
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
			"username" => FormModel::string("Login ldap")->required(),
			"password" => FormModel::password("Mot de passe")->required(),
			"remember" => FormModel::checkbox("Rester connecté")->default(true)
		]);
		if ($request->getMethod() === 'post') {
			if ($loginForm->validate($request->getBody())) {
				$dt = $loginForm->getParsedBody();
				print_r($dt);
				if (LdapUser::login($dt["username"], $dt["password"], $dt["remember"], $loginForm)) {
					Application::$app->response->redirect('/');
					return null;
				}
			} else {
				print_r("toz");
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