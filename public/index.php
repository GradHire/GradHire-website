<?php

use app\src\controller\MainController;
use app\src\controller\OpenController;
use app\src\core\lib\Psr4AutoloaderClass;
use app\src\model\Application;

require_once __DIR__ . '/../src/core/lib/Psr4AutoloaderClass.php';
require_once __DIR__ . '/../src/configuration.php';

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
$app->router->get('/creeroffre', [MainController::class, 'creeroffre']);
$app->router->post('/creeroffre', [MainController::class, 'creeroffre']);
$app->router->get('/profile/{id:\d+}/{username}', [MainController::class, 'login']);
$app->router->get('/search', [MainController::class, 'search']);
$app->router->get('/readAll', [MainController::class, 'readAll']);
$app->router->get('/mailtest', [MainController::class, 'mailtest']);
$app->router->get('/offres', [MainController::class, 'offres']);
$app->router->get('/detailOffre', [MainController::class, 'detailOffre']);
$app->router->get('/dashboard', [MainController::class, 'dashboard']);
$app->run();