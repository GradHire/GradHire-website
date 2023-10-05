<?php

namespace app\src\core\middlewares;

use app\src\core\exception\ForbiddenException;
use app\src\model\Application;
use app\src\model\Token;

class AuthMiddleware extends BaseMiddleware
{
    protected array $actions = [];

    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute(): void
    {
        if (Application::isGuest()) throw new ForbiddenException();
    }
}