<?php

use app\src\view\resources\icons\I_Candidatures;
use app\src\view\resources\icons\I_Conventions;
use app\src\view\resources\icons\I_Dashboard;
use app\src\view\resources\icons\I_Entreprises;
use app\src\view\resources\icons\I_ExplicationSimu;
use app\src\view\resources\icons\I_Importer;
use app\src\view\resources\icons\I_ListeTuteurPro;
use app\src\view\resources\icons\I_Logout;
use app\src\view\resources\icons\I_Offres;
use app\src\view\resources\icons\I_Utilisateurs;

$sections = [
    [
        'href' => 'dashboard',
        'nom' => 'Dashboard',
        'svg' => I_Dashboard::render('w-4 h-4')
    ],
    [
        'href' => 'offres',
        'nom' => 'Offres',
        'svg' => I_Offres::render('w-4 h-4')
    ],
    [
        'href' => 'utilisateurs',
        'nom' => 'Gestion roles',
        'svg' => I_Utilisateurs::render('w-4 h-4')
    ],
    [
        'href' => 'entreprises',
        'nom' => 'Entreprises',
        'svg' => I_Entreprises::render('w-4 h-4')
    ],
    [
        'href' => 'candidatures',
        'nom' => 'Candidatures',
        'svg' => I_Candidatures::render('w-4 h-4')
    ],
    [
        'href' => 'ListeTuteurPro',
        'nom' => 'Tuteurs',
        'svg' => I_ListeTuteurPro::render('w-4 h-4')
    ],
    [
        'href' => 'importer',
        'nom' => 'Imports',
        'svg' => I_Importer::render('w-4 h-4')
    ],
    [
        'href' => 'conventions',
        'nom' => 'Conventions',
        'svg' => I_Conventions::render('w-4 h-4')
    ],
    [
        'href' => 'explicationSimu',
        'nom' => 'Simulateur',
        'svg' => I_ExplicationSimu::render('w-4 h-4')
    ],
    [
        'href' => 'logout',
        'nom' => 'Déconnexion',
        'svg' => I_Logout::render('w-4 h-4')
    ]
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