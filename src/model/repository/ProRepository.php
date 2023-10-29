<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\EntrepriseRepository;

class ProRepository extends UtilisateurRepository
{
    protected static string $id_attributes = "emailUtilisateur";

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
    private static function find_account(string $email, string $password): ProRepository|null
    {
        $user = EntrepriseRepository::check_credentials($email, $password);
        if (!is_null($user)) return $user;
        return TuteurRepository::check_credentials($email, $password);
    }

    /**
     * @throws ServerErrorException
     */
    private static function check_credentials(string $email, string $password): static|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE emailUtilisateur=?");
            $statement->execute([$email]);
            $user = $statement->fetch();
            if (is_null($user) || $user === false) return null;
            $user = new static($user);
            if (!password_verify($password, $user->attributes()["hash"])) return null;
            return $user;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function full_name(): string
    {
        return $this->attributes["nomUtilisateur"];
    }
}