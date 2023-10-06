<?php

namespace app\src\model\Users;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;

class EnterpriseUser extends ProUser
{
    protected static string $view = "EntrepriseVue";
    protected static string $create_function = "creerEntreprise";

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
        } catch (\Exception $e) {
            print_r($e);
            throw new ServerErrorException();
        }
    }

    public function role(): string
    {
        return Roles::Enterprise->value;
    }
}