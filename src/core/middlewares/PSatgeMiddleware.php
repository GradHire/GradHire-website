<?php

namespace app\src\core\middlewares;

use app\src\core\exception\ForbiddenException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

class PSatgeMiddleware extends BaseMiddleware
{

    public function execute()
    {
        if (Application::isGuest() || !Auth::has_role(Roles::Student)) throw new ForbiddenException();
    }
}