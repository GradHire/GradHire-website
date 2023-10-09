<?php

namespace app\src\model\Auth;

use app\src\core\exception\ServerErrorException;
use app\src\model\Model;
use app\src\model\Users\EnterpriseUser;

class EnterpriseRegister extends Model
{
    public string $name = '';
    public string $siret = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';
    public string $password2 = '';

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'password2' => [[self::RULE_MATCH, 'match' => 'password']],
            'phone' => [self::RULE_REQUIRED],
            'siret' => [self::RULE_REQUIRED, self::RULE_INT],
            'name' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => "Adresse mail",
            'password' => "Mot de passe",
            'password2' => "Répéter mot de passe",
            'phone' => "Numéro de téléphone",
            'siret' => "Numéro SIRET",
            'name' => "Nom de l'entreprise"
        ];
    }

    /**
     * @throws ServerErrorException
     */
    public function register(): bool
    {
        try {
            $exist = EnterpriseUser::exist($this->email, $this->siret);
            if ($exist) {
                $this->addError("register", "Un compte existe déjà avec cette adresse mail ou ce numéro de siret.");
                return false;
            }
            EnterpriseUser::save([$this->name, $this->siret, $this->email, password_hash($this->password, PASSWORD_DEFAULT), $this->phone]);
            return true;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }
}