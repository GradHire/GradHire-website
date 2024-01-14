<?php

namespace app\src\controller;

use app\src\model\Application;

abstract class AbstractController
{
    public string $layout = 'guest';
    public string $action = '';

    public function render($view, $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }
}