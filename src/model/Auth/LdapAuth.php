<?php

namespace app\src\model\Auth;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Model;
use app\src\model\Token;
use app\src\model\Users\LdapUser;
use app\src\model\Users\StaffUser;
use app\src\model\Users\StudentUser;
use app\src\model\Users\User;

class LdapAuth extends Model
{
    public string $username = '';
    public string $password = '';
    public string $remember = '';

    public function rules(): array
    {
        return [
            'username' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'username' => 'Your username',
            'password' => 'Password',
            'remember' => 'Remember'
        ];
    }

    /**
     * @throws ServerErrorException
     */
    public function login(): bool
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
                CURLOPT_POSTFIELDS => array("username" => $this->username, "password" => $this->password),
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
                        ]) : new StudentUser([
                            $response->data->name,
                            $response->data->lastname,
                            $response->data->email,
                            $response->data->uid,
                            $response->data->year
                        ]);
                    } else if ($response->data->type !== "staff") {
                        $user->update(["annee" =>
                            $response->data->year]);
                    }
                    $this->generate_token($user);
                    Application::setUser($user);
                    return true;
                } else {
                    $this->addError("credentials", "Mauvais nom d'utilisateur ou mot de passe");
                }
                return false;
            }
            return false;
        } catch (\Exception $e) {
            print_r($e);
            throw new ServerErrorException();
        }
    }

    function generate_token(User $user): void
    {
        $_SESSION["role"] = $user->role();
        $_SESSION["user_id"] = $user->id();
        $duration = $this->remember === "true" ? 604800 : 3600;
        setcookie("token", Token::generate(["id" => $user->id(), "role" => $user->role()], $duration), time() + $duration);
    }
}