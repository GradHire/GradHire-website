<?php

namespace app\src\model\Auth;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Token;
use app\src\model\Users\Roles;
use app\src\model\Users\User;


class Auth
{
    /**
     * @throws ForbiddenException
     */
    public static function check_role(Roles ...$roles): void
    {
        $role = Roles::tryFrom($_SESSION["role"]);
        if (!$role || Application::isGuest() || !in_array($role, $roles)) throw new ForbiddenException();
    }

    public static function has_role(Roles ...$roles): bool
    {
        $role = Roles::tryFrom($_SESSION["role"]);
        if (!$role) return false;
        return !Application::isGuest() && in_array($role, $roles);
    }

    public static function generate_token(User $user, string $remember): void
    {
        Application::setUser($user);
        $_SESSION["role"] = $user->role();
        $_SESSION["user_id"] = $user->id();
        $_SESSION["full_name"] = $user->full_name();
        $duration = $remember === "true" ? 604800 : 3600;
        setcookie("token", Token::generate(["id" => $user->id(), "role" => $user->role(), "name" => $user->full_name()], $duration), time() + $duration);
    }

    public static function get_role_from_id(string $id): string|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT getRole(?) FROM DUAL");
            $statement->execute([$id]);
            return $statement->fetchColumn();
        } catch (\Exception $e) {
            print_r($e);
            throw new ServerErrorException();
        }
    }
}