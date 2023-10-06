<?php

namespace app\src\model\Auth;

use app\src\core\exception\ServerErrorException;
use app\src\model\Model;
use app\src\model\Users\EnterpriseUser;
use app\src\model\Users\ProUser;
use app\src\model\Users\Roles;
use app\src\model\Users\TutorUser;

class ProLogin extends Model
{
    public string $email = '';
    public string $password = '';
    public string $remember = '';

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'username' => "Adresse email",
            'password' => 'Mot de passe',
            'remember' => 'Rester connecté'
        ];
    }

    /**
     * @throws ServerErrorException
     */
    public function login(): bool
    {
        try {
            $user = self::find_account($this->email, $this->password);
            if (is_null($user)) {
                $this->addError("login", "Email ou mot de passe invalide");
                return false;
            }
            if ($user->role() == Roles::Enterprise->value && !$user->attributes()["validee"]) {
                $this->addError("login", "Votre compte n'a pas encore été validé.");
                return false;
            }
            Auth::generate_token($user, $this->remember);
            return true;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    private static function find_account(string $email, string $password): ProUser|null
    {
        $user = EnterpriseUser::check_credentials($email, $password);
        if (!is_null($user)) return $user;
        return TutorUser::check_credentials($email, $password);
    }
}