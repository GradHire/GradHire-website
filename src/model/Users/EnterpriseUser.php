<?php

namespace app\src\model\Users;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\Form\FormModel;

class EnterpriseUser extends ProUser
{
	protected static string $view = "EntrepriseVue";
	protected static string $create_function = "creerEntreprise";
	protected static string $update_function = "updateEntreprise";

	/**
	 * @throws ServerErrorException
	 */
	public static function register(array $body, FormModel $form): bool
	{
		try {
			$exist = EnterpriseUser::exist($body["email"], $body["siret"]);
			if ($exist) {
				$form->setError("Un compte existe déjà avec cette adresse mail ou ce numéro de siret.");
				return false;
			}
			$user = EnterpriseUser::save([$body["name"], $body["siret"], $body["email"], password_hash($body["password"], PASSWORD_DEFAULT), $body["phone"]]);
			Auth::generate_token($user, false);
			return true;
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function exist(string $email, string $siret): bool
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE emailutilisateur=? OR siret=?");
			$statement->execute([$email, $siret]);
			$count = $statement->rowCount();
			if ($count == 0) return false;
			return true;
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	public function role(): Roles
	{
		return Roles::Enterprise;
	}
}