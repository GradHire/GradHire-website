<?php

namespace app\src\model\repository;


use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use app\src\model\repository\UtilisateurRepository;

class LdapRepository extends UtilisateurRepository
{
	protected static string $id_attributes = "loginldap";

	/**
	 * @throws ServerErrorException
	 */
	public static function login(string $username, string $password, bool $remember, FormModel $form): bool
	{
		try {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => LDAP_API,
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
					$user = $response->data->type === "staff" ? StaffRepository::find_by_attribute($response->data->uid) : EtudiantRepository::find_by_attribute($response->data->uid);
					if (!$user) {
						$user = $response->data->type === "staff" ? StaffRepository::save([
							$response->data->lastname,
							$response->data->name,
							$response->data->email,
							$response->data->uid
						]) : EtudiantRepository::save([
							$response->data->lastname,
							$response->data->name,
							$response->data->email,
							$response->data->uid,
							$response->data->year
						]);
					} else {
						if ($user->archived()) {
							$form->setError("Ce compte à été archivé");
							return false;
						}
						if ($response->data->type !== "staff")
							$user->update_year($response->data->year);
					}
					Auth::generate_token($user, $remember);
					return true;
				} else {
					$form->setError("Mauvais nom d'utilisateur ou mot de passe");
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