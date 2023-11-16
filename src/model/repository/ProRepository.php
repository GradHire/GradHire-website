<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;

class ProRepository extends UtilisateurRepository
{
	protected static string $id_attributes = "email";

	/**
	 * @throws ServerErrorException
	 */
	public static function login(string $email, string $password, bool $remember, FormModel $form): bool
	{
		try {
			$user = self::find_account($email, $password);
			if (is_null($user)) {
				$form->setError("Email ou mot de passe invalide");
				return false;
			}
			if ($user->archived()) {
				$form->setError("Ce compte à été archivé");
				return false;
			}
			Auth::generate_token($user, $remember);
			return true;
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	private
	static function find_account(string $email, string $password): ProRepository|null
	{
		$user = EntrepriseRepository::check_credentials($email, $password);
		if (!is_null($user)) return $user;
		return TuteurEntrepriseRepository::check_credentials($email, $password);
	}

	/**
	 * @throws ServerErrorException
	 */
	private
	static function check_credentials(string $email, string $password): static|null
	{
		try {
			$statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE email=?");
			$statement->execute([$email]);
			$user = $statement->fetch();
			if (is_null($user) || $user === false) return null;
			$user = new static($user);
			if (!password_verify($password, $user->attributes()["hash"])) return null;
			return $user;
		} catch
		(\Exception) {
			throw new ServerErrorException();
		}
	}

	public static function changePassword(string $password, string $new, FormModel $form): bool
	{
		try {
			$id = Application::getUser()->id();
			$statement = Database::get_conn()->prepare("SELECT * FROM ComptePro WHERE idUtilisateur=?");
			$statement->execute([$id]);
			$user = $statement->fetch();
			if (is_null($user) || $user === false || !password_verify($password, $user["hash"])) {
				$form->setError("Mot de passe invalide");
				return false;
			}
			$hash = password_hash($new, PASSWORD_DEFAULT);
			$statement = Database::get_conn()->prepare("UPDATE ComptePro SET hash=? WHERE idUtilisateur=?");
			$statement->execute([$hash, $id]);
			return true;
		} catch (\Exception) {
			return false;
		}
	}
}