<?php

namespace app\src\model\Users;


use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\Form\FormModel;

class LdapUser extends User
{
	protected static string $id_attributes = "loginldap";

	public static function login(string $username, string $password, bool $remember, FormModel $form): bool
	{
		try {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://webinfo.iutmontp.univ-montp2.fr/~broutym/SAE/ldap.php',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array("username" => $username, "password" => $password),
				CURLOPT_SSL_VERIFYPEER => false,
			));

			$response = curl_exec($curl);
			if ($response !== false) {
				$response = json_decode($response);
				if ($response->success === "true") {
					$user = $response->data->type === "staff" ? StaffUser::find_by_attribute($response->data->uid) : StudentUser::find_by_attribute($response->data->uid);
					if (!$user) {
						$user = $response->data->type === "staff" ? StaffUser::save([
							$response->data->name,
							$response->data->lastname,
							$response->data->email,
							$response->data->uid
						]) : StudentUser::save([
							$response->data->name,
							$response->data->lastname,
							$response->data->email,
							$response->data->uid,
							$response->data->year
						]);
					} else if ($response->data->type !== "staff") {
						$user->update_year($response->data->year);
					}
					Auth::generate_token($user, $remember);
					return true;
				} else {
					$form->add_error("Mauvais nom d'utilisateur ou mot de passe");
				}
				return false;
			}
			return false;
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}

	public function full_name(): string
	{
		return $this->attributes['prenomutilisateurldap'] . " " . $this->attributes['nomutilisateur'];
	}
}