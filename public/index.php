<?php

use app\controllers\AboutController;
use app\controllers\SiteController;
use app\core\Application;
use app\core\lib\Psr4AutoloaderClass;

require_once __DIR__ . '/../core/lib/Psr4AutoloaderClass.php';

$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('app', __DIR__ . '/../');


$config = ['userClass' => \app\models\User::class];

$app = new Application(dirname(__DIR__), $config);

//$app->on(Application::EVENT_BEFORE_REQUEST, function () {
//    echo "Before request";
//});
//
//$app->on(Application::EVENT_AFTER_REQUEST, function () {
//    echo "After request";
//});

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/register', [SiteController::class, 'register']);
$app->router->post('/register', [SiteController::class, 'register']);
$app->router->get('/login', [SiteController::class, 'login']);
$app->router->get('/login/{id}', [SiteController::class, 'login']);
$app->router->post('/login', [SiteController::class, 'login']);
$app->router->get('/logout', [SiteController::class, 'logout']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->get('/about', [AboutController::class, 'index']);
$app->router->get('/profile', [SiteController::class, 'profile']);
$app->router->get('/profile/{id:\d+}/{username}', [SiteController::class, 'login']);
$app->run();
