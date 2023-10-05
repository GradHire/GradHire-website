<?php

namespace app\src\model;

use app\src\controller\Controller;
use app\src\core\exception\ForbiddenException;
use app\src\model\Users\User;

use app\src\core\db\Database;

class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    public static Application $app;
    public static string $ROOT_DIR;
    public static ?User $user = null;
    public string $layout = 'main';
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public View $view;
    protected array $eventListeners = [];

    public function __construct($rootDir, $config)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        if (isset($_COOKIE["token"])) {
            $user_token = Token::verify($_COOKIE["token"]);
            if (!is_null($user_token)) {
                $_SESSION["role"] = $user_token["role"];
                $_SESSION["user_id"] = $user_token["id"];
            } else session_destroy();
        } else {
            session_destroy();
        }
    }

    public static function setUser(User $user)
    {
        self::$user = $user;
    }
        $userId = Application::$app->session->get('user');
        if ($userId) {
            $key = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$key => $userId]);
        }
    }

    public static function isGuest(): bool
    {
        return !isset($_COOKIE["token"]) || !isset($_SESSION["user_id"]);
    }

    public function run(): void
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            echo $this->router->renderView('_error', [
                'exception' => $e,
            ]);
        }
    }

    public function triggerEvent($eventName): void
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function on($eventName, $callback): void
    {
        $this->eventListeners[$eventName][] = $callback;
    }
}