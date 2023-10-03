<?php

namespace app\src\core\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}