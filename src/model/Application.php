<?php

namespace app\src\model;

use app\src\controller\Controller;
use app\src\core\exception\ServerErrorException;
use app\src\model\Auth\Auth;
use app\src\model\Users\EnterpriseUser;
use app\src\model\Users\StaffUser;
use app\src\model\Users\StudentUser;
use app\src\model\Users\TutorUser;
use app\src\model\Users\User;

class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    public static Application $app;
    public static string $ROOT_DIR;
    private static ?User $user = null;
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
                $_SESSION["full_name"] = $user_token["name"];
            } else session_destroy();
        } else {
            session_destroy();
        }
    }

    public static function go_home(): void
    {
        header("Location: /");
    }

    /**
     * @throws ServerErrorException
     */
    public static function getUser(): User|null
    {
        if (!is_null(self::$user))
            return self::$user;
        if (self::isGuest()) return null;
        $role = Auth::get_role_from_id($_SESSION["user_id"]);
        if (is_null($role)) return null;
        switch ($role) {
            case "tuteur":
                self::$user = TutorUser::find_by_id($_SESSION["user_id"]);
                break;
            case "etudiant":
                self::$user = StudentUser::find_by_id($_SESSION["user_id"]);
                break;
            case "entreprise":
                self::$user = EnterpriseUser::find_by_id($_SESSION["user_id"]);
                break;
            case "staff":
                self::$user = StaffUser::find_by_id($_SESSION["user_id"]);
                break;
        }
        return self::$user;
    }

    public static function setUser(User $user): void
    {
        self::$user = $user;
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