<?php

use app\src\controller\AuthController;
use app\src\controller\CandidatureController;
use app\src\controller\ConventionsController;
use app\src\controller\DashboardController;
use app\src\controller\OffreController;
use app\src\controller\OpenController;
use app\src\controller\PostulerController;
use app\src\controller\PstageController;
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
$app->router->get('/about', 'about');

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

$app->router->get('/password', [AuthController::class, 'modifierMdp']);
$app->router->post('/password', [AuthController::class, 'modifierMdp']);

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

$app->router->get('/subscribe', [OffreController::class, 'subscribeNewsletter']);

$app->router->get('/offres', [OffreController::class, 'offres']);

$app->router->get('/offres/create', [OffreController::class, 'creeroffre']);
$app->router->post('/offres/create', [OffreController::class, 'creeroffre']);
$app->router->get('/offres/create/{id:\d+}', [OffreController::class, 'creeroffre']);
$app->router->post('/offres/create/{id:\d+}', [OffreController::class, 'creeroffre']);

$app->router->get('/offres/{id:\d+}', [OffreController::class, 'detailOffre']);

$app->router->get('/offres/{id:\d+}/postuler', [OffreController::class, 'postuler']);
$app->router->post('/offres/{id:\d+}/postuler', [OffreController::class, 'postuler']);

$app->router->post('/offres/{id:\d+}/edit', [OffreController::class, 'editOffre']);
$app->router->post('/offres/{id:\d+}/archive', [OffreController::class, 'archiveOffre']);

$app->router->post('/offres/{id:\d+}/edit', [OffreController::class, 'editOffre']);

$app->router->get('/offres/{id:\d+}/edit', [OffreController::class, 'editOffre']);

$app->router->get('/offres/{id:\d+}/validate', [OffreController::class, 'validateOffre']);
$app->router->get('/offres/{id:\d+}/archive', [OffreController::class, 'archiveOffre']);

$app->router->get('/offres/maps', [OffreController::class, 'mapsOffres']);

// TestController

$app->router->get('/user_test/{id}', [TestController::class, 'user_test']);

$app->router->get('/dashboard', [DashboardController::class, 'showDashboard']);
$app->router->post('/dashboard', [DashboardController::class, 'showDashboard']);
$app->router->get('/dashboard_old', [DashboardController::class, 'showDashboardOld']);
$app->router->post('/dashboard_old', [DashboardController::class, 'showDashboardOld']);


// CandidatureContoller
$app->router->get('/candidatures', [CandidatureController::class, 'candidatures']);
$app->router->get('/candidatures/contacter/{id}', [CandidatureController::class, 'contacterEntreprise']);
$app->router->post('/candidatures/contacter/{id}', [CandidatureController::class, 'contacterEntreprise']);
$app->router->get('/candidatures/{idOffre}/{idUtilisateur}', [CandidatureController::class, 'candidatures']);
$app->router->post('/candidatures', [CandidatureController::class, 'candidatures']);
$app->router->get('/candidatures/refuser/{idEtudiant}/{idOffre}', [CandidatureController::class, 'refuser']);
$app->router->get('/candidatures/validerEntreprise/{idEtudiant}/{idOffre}', [CandidatureController::class, 'validerAsEntreprise']);
$app->router->get('/candidatures/validerEtudiant/{idEtudiant}/{idOffre}', [CandidatureController::class, 'validerAsEtudiant']);

// DashboardController


$app->router->post('/utilisateurs/{id}/role', [DashboardController::class, 'role']);

$app->router->get('/utilisateurs/{id}/archiver', [DashboardController::class, 'archiver']);

$app->router->get('/utilisateurs', [DashboardController::class, 'utilisateurs']);
$app->router->get('/utilisateurs/{id}', [DashboardController::class, 'utilisateurs']);
$app->router->post('/utilisateurs/{id}', [DashboardController::class, 'utilisateurs']);

$app->router->get('/ListeTuteurPro', [DashboardController::class, 'ListeTuteurPro']);
$app->router->post('/ListeTuteurPro', [DashboardController::class, 'ListeTuteurPro']);

$app->router->get('/importer', [PstageController::class, 'importercsv']);
$app->router->post('/importer', [PstageController::class, 'importercsv']);

$app->router->get('/importerStudea', [PstageController::class, 'importerStudea']);
$app->router->post('/importerStudea', [PstageController::class, 'importerStudea']);

$app->router->get('/page1', [TestController::class, 'page1']);
$app->router->post('/page1', [TestController::class, 'page1']);

$app->router->get('/page2', [TestController::class, 'page2']);
$app->router->post('/page2', [TestController::class, 'page2']);

$app->router->get('/page3', [TestController::class, 'page3']);
$app->router->post('/page3', [TestController::class, 'page3']);

$app->router->get('/explicationSimu', [PstageController::class, 'explicationSimu']);


$app->router->get('/simulateur', [PstageController::class, 'simulateur']);
$app->router->post('/simulateur', [PstageController::class, 'simulateur']);

$app->router->get('/simulateurOffre', [PstageController::class, 'simulateurOffre']);
$app->router->post('/simulateurOffre', [PstageController::class, 'simulateurOffre']);

$app->router->get('/previewOffre', [PstageController::class, 'previewOffre']);

$app->router->get('/creerEntreprise', [PstageController::class, 'creerEntreprise']);
$app->router->post('/creerEntreprise', [PstageController::class, 'creerEntreprise']);

$app->router->get('/simulateurServiceAccueil', [PstageController::class, 'simulateurServiceAccueil']);
$app->router->post('/simulateurServiceAccueil', [PstageController::class, 'simulateurServiceAccueil']);

$app->router->get('/creerService', [PstageController::class, 'creerService']);
$app->router->post('/creerService', [PstageController::class, 'creerService']);

$app->router->get('/previewServiceAccueil', [PstageController::class, 'previewServiceAccueil']);

$app->router->get('/simulateurTuteur', [PstageController::class, 'simulateurTuteur']);

$app->router->get('/creerTuteur', [PstageController::class, 'creerTuteur']);
$app->router->post('/creerTuteur', [PstageController::class, 'creerTuteur']);

$app->router->get('/simulateurCandidature', [PstageController::class, 'simulateurCandidature']);
$app->router->post('/simulateurCandidature', [PstageController::class, 'simulateurCandidature']);

$app->router->get('/previewCandidature', [PstageController::class, 'previewCandidature']);

$app->router->get('/simulateurProfReferent', [PstageController::class, 'simulateurProfReferent']);
$app->router->post('/simulateurProfReferent', [PstageController::class, 'simulateurProfReferent']);

$app->router->get('/simulateurSignataire', [PstageController::class, 'simulateurSignataire']);
$app->router->post('/simulateurSignataire', [PstageController::class, 'simulateurSignataire']);

$app->router->get('/creerSignataire', [PstageController::class, 'creerSignataire']);
$app->router->post('/creerSignataire', [PstageController::class, 'creerSignataire']);

$app->router->get('/previewSignataire', [PstageController::class, 'previewSignataire']);

$app->router->get('/visuRecapConv', [PstageController::class, 'visuRecapConv']);

$app->router->get('/validersimulation', [PstageController::class, 'validersimulation']);

$app->router->get('/gererSimulPstage', [PstageController::class, 'gererSimulPstage']);
$app->router->get('/gererSimulPstage/valide/{id}', [PstageController::class, 'gererSimulPstagevalide']);
$app->router->get('/gererSimulPstage/refuse/{id}', [PstageController::class, 'gererSimulPstagerefuse']);


// ConventionController

$app->router->get('/conventions', [ConventionsController::class, 'afficherListeConventions']);
$app->router->get('/conventions/{id:\d+}', [ConventionsController::class, 'detailConvention']);
$app->router->get('/conventions/{id:\d+}/edit', [ConventionsController::class, 'editConvention']);
$app->router->post('/conventions/{id:\d+}/edit', [ConventionsController::class, 'editConvention']);
$app->router->get('/validateConventionPedagogiquement/{id:\d+}', [ConventionsController::class, 'validateConventionPedagogiquement']);
$app->router->get('/unvalidateConventionPedagogiquement/{id:\d+}', [ConventionsController::class, 'unvalidateConventionPedagogiquement']);
$app->router->get('/unvalidateConvention/{id:\d+}', [ConventionsController::class, 'unvalidateConvention']);
$app->router->get('/validateConvention/{id:\d+}', [ConventionsController::class, 'validateConvention']);

// PostulerController

$app->router->get('/postuler/seProposer/{id:\d+}', [PostulerController::class, 'se_proposer']);
$app->router->get('/postuler/seDeproposer/{id:\d+}', [PostulerController::class, 'se_deproposer']);
$app->router->get('/postuler/listeTuteur/{idOffre}/{idUser}', [PostulerController::class, 'listeTuteurPostuler']);
$app->router->get('/postuler/accepter_as_tuteur/{idUser}/{idOffre}', [PostulerController::class, 'accepterAsTuteur']);
$app->router->get('/postuler/unaccepte_as_tuteur/{idUser}/{idOffre}', [PostulerController::class, 'annulerAsTuteur']);

$app->run();
