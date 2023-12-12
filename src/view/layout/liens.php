<?php

use app\src\view\resources\icons\I_Calendrier;
use app\src\view\resources\icons\I_Candidatures;
use app\src\view\resources\icons\I_Conventions;
use app\src\view\resources\icons\I_Dashboard;
use app\src\view\resources\icons\I_Entreprises;
use app\src\view\resources\icons\I_ExplicationSimu;
use app\src\view\resources\icons\I_Importer;
use app\src\view\resources\icons\I_Link;
use app\src\view\resources\icons\I_ListeTuteurPro;
use app\src\view\resources\icons\I_Logout;
use app\src\view\resources\icons\I_Offres;
use app\src\view\resources\icons\I_Utilisateurs;


$sections = array();
$actions = array();

$sections['S01'] = [
    'href' => 'dashboard',
    'nom' => 'Dashboard',
    'svg' => I_Dashboard::render('w-4 h-4')
];

$sections['S02'] = [
    'href' => 'offres',
    'nom' => 'Offres',
    'svg' => I_Offres::render('w-4 h-4')
];

$sections['S03'] = [
    'href' => 'utilisateurs',
    'nom' => 'Gestion roles',
    'svg' => I_Utilisateurs::render('w-4 h-4')
];

$sections['S04'] = [
    'href' => 'entreprises',
    'nom' => 'Entreprises',
    'svg' => I_Entreprises::render('w-4 h-4')
];

$sections['S05'] = [
    'href' => 'candidatures',
    'nom' => 'Candidatures',
    'svg' => I_Candidatures::render('w-4 h-4')
];

$sections['S06'] = [
    'href' => 'ListeTuteurPro',
    'nom' => 'Tuteurs',
    'svg' => I_ListeTuteurPro::render('w-4 h-4')
];

$sections['S07'] = [
    'href' => 'importer',
    'nom' => 'Imports',
    'svg' => I_Importer::render('w-4 h-4')
];

$sections['S08'] = [
    'href' => 'conventions',
    'nom' => 'Conventions',
    'svg' => I_Conventions::render('w-4 h-4')
];

$sections['S09'] = [
    'href' => 'explicationSimu',
    'nom' => 'Simulateur',
    'svg' => I_ExplicationSimu::render('w-4 h-4')
];

$sections['S10'] = [
    'href' => 'calendar',
    'nom' => 'Calendrier',
    'svg' => I_Calendrier::render('w-4 h-4')
];

$sections['S11'] = [
    'href' => 'logout',
    'nom' => 'Déconnexion',
    'svg' => I_Logout::render('w-4 h-4')
];

$actions['A01'] = [
    'href' => 'offres?sujet=&type%5B%5D=stage&year=all&duration=all&gratification%5B%5D=4.05&gratification%5B%5D=15&action=',
    'nom' => 'Offres de stage',
    'svg' => I_Link::render('w-4 h-4')
];

$actions['A02'] = [
    'href' => 'offres?sujet=&type%5B%5D=alternance&year=all&duration=all&gratification%5B%5D=4.05&gratification%5B%5D=15&action=',
    'nom' => 'Offres d\'alternance',
    'svg' => I_Link::render('w-4 h-4')
];

$actions['A03'] = [
    'href' => 'gererSimulPstage',
    'nom' => 'Gerer ces simulations Pstage',
    'svg' => I_Link::render('w-4 h-4')
];

$actions['A04'] = [
    'href' => 'offres/create',
    'nom' => 'Creer offre',
    'svg' => I_Link::render('w-4 h-4')
];

$actions['A05'] = [
    'href' => 'harceler',
    'nom' => 'Harceler',
    'svg' => I_Link::render('w-4 h-4')
];



//$lienAccueil->render();
//
//if (!Application::isGuest()) {
//    if (!Auth::has_role(Roles::ChefDepartment)) $lienOffres->render();
//    else $lienUtilisateurs->render();
//    if (!Auth::has_role(Roles::Enterprise, Roles::Tutor, Roles::ChefDepartment)) $lienEntreprises->render();
//    if (Auth::has_role(Roles::Student, Roles::Teacher, Roles::Tutor, Roles::Enterprise)) $lienCandidatures->render();
//    if (Auth::has_role(Roles::Enterprise)) {
//        $lienCreate->render();
//        $lienTuteurs->render();
//    }
//    if (Auth::has_role(Roles::Manager, Roles::Staff)) {
//        $lienUtilisateurs->render();
//        $lienCandidatures->render();
//        $lienTuteurs->render();
//        $lienImport->render();
//    }
//    if (Auth::has_role(Roles::Student)) $lienExplicationSimu->render();
//    if (Auth::has_role(Roles::Enterprise, Roles::Student, Roles::Manager, Roles::Staff)) $lienConventions->render();
//}