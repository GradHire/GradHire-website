<?php

namespace app\src\model\Auth;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Token;
use app\src\model\Users\EnterpriseUser;
use app\src\model\Users\Roles;
use app\src\model\Users\StaffUser;
use app\src\model\Users\StudentUser;
use app\src\model\Users\TutorUser;
use app\src\model\Users\User;


class Auth
{
    /**
     * @throws ForbiddenException
     */
    public static function check_role(Roles ...$roles): void
    {
        if (Application::isGuest()) header("Location: /login");
        if (!in_array(Application::getUser()->role(), $roles)) throw new ForbiddenException();
    }

    public static function has_role(Roles ...$roles): bool
    {
        return !Application::isGuest() && in_array(Application::getUser()->role(), $roles);
    }

    public static function generate_token(User $user, string $remember): void
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        Application::setUser($user);
        $duration = $remember === "true" ? 604800 : 3600;
        setcookie("token", Token::generate(["id" => $user->id()], $duration), time() + $duration);
    }

    /**
     * @throws ServerErrorException
     */
    public static function load_user_by_id(string $id): User|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT getRole(?) FROM DUAL");
            $statement->execute([$id]);
            $role = $statement->fetchColumn();
            if (!$role) return null;
            $role = Roles::tryFrom($role);
            switch ($role) {
                case Roles::Tutor:
                    return TutorUser::find_by_id($id);
                case Roles::Student:
                    return StudentUser::find_by_id($id);
                case Roles::Enterprise:
                    return EnterpriseUser::find_by_id($id);
                case Roles::Manager:
                case Roles::Staff:
                case Roles::Teacher:
                    return StaffUser::find_by_id($id);
            }
        } catch (\Exception) {
            throw new ServerErrorException();
        }
        return null;
    }

    public static function logout()
    {
        unset($_COOKIE["token"]);
        setcookie('token', '', -1);
        session_destroy();
    }
}