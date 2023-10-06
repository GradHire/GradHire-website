<?php

namespace app\src\model\Auth;

use app\src\core\exception\ForbiddenException;
use app\src\model\Application;
use app\src\model\Users\Roles;


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
}