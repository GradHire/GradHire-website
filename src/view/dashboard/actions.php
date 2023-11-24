<?php

use app\src\core\components\Lien;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$linkOffres = new Lien('/offres', 'Offres');
$linkUtilisateurs = new Lien('/utilisateurs', 'Gestion roles');
$linkEntreprises = new Lien('/entreprises', 'Entreprises');
$linkCandidatures = new Lien('/candidatures', 'Candidatures');
$linkCreate = new Lien('/offres/create', 'Créer une offre');
$linkTuteurs = new Lien('/ListeTuteurPro', 'Tuteurs');
$linkImport = new Lien('/importer', 'Import');
$linkImportStudeants = new Lien('/importerStudea', 'Import Studea');
$linkConventions = new Lien('/conventions', 'Conventions');
$linkExplicationSimu = new Lien('/explicationSimu', 'Simulateur');


if (!Application::isGuest()) {
    if (!Auth::has_role(Roles::ChefDepartment)) echo $linkOffres->render();
    else echo $linkUtilisateurs->render();
    if (!Auth::has_role(Roles::Enterprise, Roles::Tutor, Roles::ChefDepartment)) echo $linkEntreprises->render();
    if (Auth::has_role(Roles::Student, Roles::Teacher, Roles::Tutor, Roles::TutorTeacher, Roles::Enterprise)) echo $linkCandidatures->render();
    if (Auth::has_role(Roles::Enterprise)) {
        echo $linkCreate->render();
        echo $linkTuteurs->render();
    }
    if (Auth::has_role(Roles::Manager, Roles::Staff)) {
        echo $linkUtilisateurs->render();
        echo $linkCandidatures->render();
        echo $linkTuteurs->render();
        echo $linkImport->render();
        echo $linkImportStudeants->render();
    }
    if (Auth::has_role(Roles::Student)) echo $linkExplicationSimu->render();
    if (Auth::has_role(Roles::Enterprise, Roles::Student, Roles::Manager, Roles::Staff)) echo $linkConventions->render();
}

?>