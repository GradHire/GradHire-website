<?php

use app\src\controller\AuthController;
use app\src\controller\DashboardController;
use app\src\controller\OffreController;
use app\src\controller\OpenController;
use app\src\controller\TestController;
use app\src\controller\UserController;
use app\src\core\lib\Psr4AutoloaderClass;
use app\src\model\Application;

require_once __DIR__ . '/../src/core/lib/Psr4AutoloaderClass.php';
require_once __DIR__ . '/../src/config.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('app', __DIR__ . '/../');

$app = new Application(dirname(__DIR__));

$app->router->get('/', 'home');

// AuthController

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/registerTutor/{token}', [AuthController::class, 'createTutor']);
$app->router->post('/registerTutor/{token}', [AuthController::class, 'createTutor']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/pro_login', [AuthController::class, 'pro_login']);
$app->router->post('/pro_login', [AuthController::class, 'pro_login']);

$app->router->get('/logout', [AuthController::class, 'logout']);

// UserController

$app->router->get('/profile', [UserController::class, 'profile']);
$app->router->get('/profile/{id}', [UserController::class, 'profile']);

$app->router->get('/edit_profile', [UserController::class, 'edit_profile']);
$app->router->get('/edit_profile/{id}', [UserController::class, 'edit_profile']);
$app->router->post('/edit_profile', [UserController::class, 'edit_profile']);
$app->router->post('/edit_profile/{id}', [UserController::class, 'edit_profile']);

$app->router->get('/entreprises', [UserController::class, 'entreprises']);
$app->router->get('/entreprises/{id:\d+}', [UserController::class, 'entreprises']);

// OffreController

$app->router->get('/offres', [OffreController::class, 'offres']);

$app->router->get('/offres/create', [OffreController::class, 'creeroffre']);
$app->router->post('/offres/create', [OffreController::class, 'creeroffre']);

$app->router->get('/offres/{id:\d+}', [OffreController::class, 'offres']);

$app->router->get('/offres/{id:\d+}/postuler', [OffreController::class, 'postuler']);
$app->router->post('/offres/{id:\d+}/postuler', [OffreController::class, 'postuler']);

$app->router->post('/offres/{id:\d+}/edit', [OffreController::class, 'editOffre']);
$app->router->post('/offres/{id:\d+}/archive', [OffreController::class, 'archiveOffre']);

$app->router->post('/offres/{id:\d+}/edit', [OffreController::class, 'editOffre']);

$app->router->get('/offres/{id:\d+}/edit', [OffreController::class, 'editOffre']);

$app->router->get('/offres/{id:\d+}/validate', [OffreController::class, 'validateOffre']);
$app->router->get('/offres/{id:\d+}/archive', [OffreController::class, 'archiveOffre']);

// TestController

$app->router->get('/user_test/{id}', [TestController::class, 'user_test']);

// DashboardController

$app->router->get('/candidatures', [DashboardController::class, 'candidatures']);
$app->router->get('/candidatures/{id:\d+}', [DashboardController::class, 'candidatures']);
$app->router->post('/candidatures', [DashboardController::class, 'candidatures']);

$app->router->get('/utilisateurs/{id}/archiver', [DashboardController::class, 'archiver']);

$app->router->get('/utilisateurs', [DashboardController::class, 'utilisateurs']);
$app->router->get('/utilisateurs/{id}', [DashboardController::class, 'utilisateurs']);
$app->router->post('/utilisateurs/{id}', [DashboardController::class, 'utilisateurs']);

$app->router->get('/ListeTuteurPro', [DashboardController::class, 'ListeTuteurPro']);
$app->router->post('/ListeTuteurPro', [DashboardController::class, 'ListeTuteurPro']);

$app->router->get('/importer', [MainController::class, 'importercsv']);
$app->router->post('/importer', [MainController::class, 'importercsv']);


$app->run();
