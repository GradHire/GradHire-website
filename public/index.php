<?php

use app\src\controller\MainController;
use app\src\controller\OpenController;
use app\src\core\lib\Psr4AutoloaderClass;
use app\src\model\Application;

require_once __DIR__ . '/../src/core/lib/Psr4AutoloaderClass.php';
require_once __DIR__ . '/../src/Configuration.php';

session_start();

$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('app', __DIR__ . '/../');

$config = ['userClass' => \app\src\model\User::class];

$app = new Application(dirname(__DIR__), $config);

//$app->on(Application::EVENT_BEFORE_REQUEST, function () {
//    echo "Before request";
//});
//
//$app->on(Application::EVENT_AFTER_REQUEST, function () {
//    echo "After request";
//});

$app->router->get('/', [MainController::class, 'home']);
$app->router->get('/register', [MainController::class, 'register']);
$app->router->post('/register', [MainController::class, 'register']);
$app->router->get('/login', [MainController::class, 'login']);
$app->router->get('/login/{id}', [MainController::class, 'login']);
$app->router->post('/login', [MainController::class, 'login']);
$app->router->get('/logout', [MainController::class, 'logout']);
$app->router->get('/contact', [MainController::class, 'contact']);
$app->router->get('/about', [OpenController::class, 'index']);
$app->router->get('/profile', [MainController::class, 'profile']);
$app->router->get('/profile/{id:\d+}/{username}', [MainController::class, 'login']);
$app->run();
