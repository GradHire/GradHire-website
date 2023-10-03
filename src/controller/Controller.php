<?php

namespace app\src\controller;

use app\src\core\middlewares\BaseMiddleware;
use app\src\model\Application;

abstract class Controller
{
	public string $layout = 'main';
	public string $action = '';

	protected array $middlewares = [];

	public function setLayout($layout): void
	{
		$this->layout = $layout;
	}

	public function render($view, $params = []): string
	{
		return Application::$app->router->renderView($view, $params);
	}

	public function registerMiddleware(BaseMiddleware $middleware): void
	{
		$this->middlewares[] = $middleware;
	}

	public function getMiddlewares(): array
	{
		return $this->middlewares;
	}
}