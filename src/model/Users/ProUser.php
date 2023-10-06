<?php

namespace app\src\model\Users;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;

class ProUser extends User
{
    protected static string $id_attributes = "emailutilisateur";

    /**
     * @throws ServerErrorException
     */
    public static function check_credentials(string $email, string $password): static|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE emailutilisateur=?");
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
        return $this->attributes["nomutilisateur"];
    }
}