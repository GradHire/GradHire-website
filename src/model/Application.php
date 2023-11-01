<?php

namespace app\src\model;

use app\src\controller\AbstractController;
use app\src\core\exception\ServerErrorException;
use app\src\model\repository\UtilisateurRepository;

class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    public static Application $app;
    public static string $ROOT_DIR;
    private static ?UtilisateurRepository $user = null;
    public string $layout = 'main';
    public Router $router;
    public Request $request;
    public Response $response;
    public ?AbstractController $controller = null;
    public View $view;
    protected array $eventListeners = [];

    /**
     * @throws ServerErrorException
     */
    public function __construct($rootDir)
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
                if (!isset($_SESSION["user"]))
                    self::setUser(Auth::load_user_by_id($user_token["id"]));
            } else
                session_destroy();
        } else
            session_destroy();
    }

    public static function go_home(): void
    {
        header("Location: /");
    }

    public static function getUser(): UtilisateurRepository|null
    {
        if (!is_null(self::$user)) {
            return self::$user;
        }
        if (self::isGuest()) {
            return null;
        }
        return unserialize($_SESSION["user"]);
    }

    public static function setUser(UtilisateurRepository|null $user): void
    {
        if (is_null($user) || $user->archived()) {
            Auth::logout();
            return;
        }
        $_SESSION["user"] = serialize($user);
        self::$user = $user;
    }

    public static function isGuest(): bool
    {
        return !isset($_COOKIE["token"]) || !isset($_SESSION["user"]);
    }

    public static function getRedirect(): string
    {
        return "redirect=" . urlencode('http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    public static function redirectFromParam(string $else): void
    {
        if (isset($_GET["redirect"]))
            header("Location: " . urldecode($_GET["redirect"]));
        else
            header("Location: " . $else);
    }

    public function run(): void
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            echo $this->router->renderView('_error', ['exception' => $e,]);
        }
    }

    public
    function triggerEvent($eventName): void
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public
    function on($eventName, $callback): void
    {
        $this->eventListeners[$eventName][] = $callback;
    }
}