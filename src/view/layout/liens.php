<?php

use app\src\core\components\Lien;
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

$lienAccueil = new Lien([
    'href' => 'dashboard',
    'nom' => 'Dashboard',
    'svg' => I_Dashboard::render('w-4 h-4')]);

$lienOffres = new Lien([
    'href' => 'offres',
    'nom' => 'Offres',
    'svg' => I_Offres::render('w-4 h-4')]);

$logout = new Lien([
    'href' => 'logout',
    'nom' => 'Déconnexion',
    'svg' => I_Logout::render('w-4 h-4')]);

$lienUtilisateurs = new Lien([
    'href' => 'utilisateurs',
    'nom' => 'Gestion roles',
    'svg' => I_Utilisateurs::render('w-4 h-4')]);

$lienEntreprises = new Lien([
    'href' => 'entreprises',
    'nom' => 'Entreprises',
    'svg' => I_Entreprises::render('w-4 h-4')]);

$lienCandidatures = new Lien([
    'href' => 'candidatures',
    'nom' => 'Candidatures',
    'svg' => I_Candidatures::render('w-4 h-4')]);

$lienTuteurs = new Lien([
    'href' => 'ListeTuteurPro',
    'nom' => 'Tuteurs',
    'svg' => I_ListeTuteurPro::render('w-4 h-4')]);

$lienImport = new Lien([
    'href' => 'importer',
    'nom' => 'Imports',
    'svg' => I_Importer::render('w-4 h-4')]);

$lienConventions = new Lien([
    'href' => 'conventions',
    'nom' => 'Conventions',
    'svg' => I_Conventions::render('w-4 h-4')]);

$lienExplicationSimu = new Lien([
    'href' => 'explicationSimu',
    'nom' => 'Simulateur',
    'svg' => I_ExplicationSimu::render('w-4 h-4')]);