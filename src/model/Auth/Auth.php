<?php

namespace app\src\model\Auth;

use app\src\core\exception\ForbiddenException;
use app\src\model\Application;

class Auth
{
    /**
     * @throws ForbiddenException
     */
    public static function check_role(array $roles)
    {
        if (!self::has_role($roles)) throw new ForbiddenException();
    }

    public static function has_role(array $roles): bool
    {
        return !Application::isGuest() && in_array($_SESSION["role"], $roles);
    }
}