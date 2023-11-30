<?php

namespace app\src\model;

use app\src\controller\AbstractController;

class View
{
    public string $title = '';
    public static string $currentSection = '';

    public function renderView($view, array $params)
    {
        $layoutName = Application::$app->layout;
        if (Application::$app->controller) {
            Application::isGuest() ? $layoutName = 'guest' : $layoutName = 'main';
        }
        $viewContent = $this->renderViewOnly($view, $params);
        ob_start();
        include_once Application::$ROOT_DIR."/src/view/layout/$layoutName.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderViewOnly($view, array $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/src/view/$view.php";
        return ob_get_clean();
    }

    public static function getCurrentSection(): string
    {
        return self::$currentSection;
    }

    public static function setCurrentSection(string $currentSection): void
    {
        self::$currentSection = $currentSection;
    }
}