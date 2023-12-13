<?php

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