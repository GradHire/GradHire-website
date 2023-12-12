<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use Exception;

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
		} catch (Exception) {
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
		(Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkForgetToken(string $token): array|null
	{
		return self::Fetch("SELECT * FROM modifiermdp WHERE token=?", [$token]);
	}

	/**
	 * @throws ServerErrorException
	 * @throws Exception
	 */
	public static function forgetPassword(string $email): void
	{
		$userId = UtilisateurRepository::getIdByEmail($email);
		if (!$userId) return;
		$token = self::Fetch("SELECT * FROM modifiermdp WHERE idutilisateur=?", [$userId]);
		if (!$token) {
			self::createForgetToken($email, $userId);
		} else {
			$now = new \DateTime();
			$tokenDate = new \DateTime($token["datecreation"]);
			$diff = $now->diff($tokenDate);
			if ($diff->h >= 1 || $diff->days > 0)
				self::createForgetToken($email, $userId);
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	private static function createForgetToken(string $email, int $id): void
	{
		try {
			self::deleteForgetToken($id);
			$token = substr(bin2hex(random_bytes(20)), 0, 20);
			self::Execute("INSERT INTO modifiermdp (idutilisateur, token) values(?,?)", [$id, $token]);
			MailRepository::send_mail([$email], "Changer mon mot de passe", "Voici le lien pour changer votre mot de passe: " . HOST . "/forgetPassword/" . $token . "<br>Ce lien est valide pendant 1 heure.");
		} catch (Exception) {
			throw new ServerErrorException();
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function deleteForgetToken(int $id): void
	{
		self::Execute("DELETE FROM modifiermdp WHERE idutilisateur=?", [$id]);
	}

	public static function changePassword(string $password, string $new, FormModel $form): bool
	{
		try {
			$id = Application::getUser()->id();
			$user = self::Fetch("SELECT * FROM ComptePro WHERE idUtilisateur=?", [$id]);
			if (is_null($user) || !password_verify($password, $user["hash"])) {
				$form->setError("Mot de passe invalide");
				return false;
			}
			self::setNewPassword($new, $id);
			return true;
		} catch (Exception) {
			return false;
		}
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function setNewPassword(string $password, int $id): void
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);
		self::Execute("UPDATE ComptePro SET hash=? WHERE idUtilisateur=?", [$hash, $id]);
	}
}