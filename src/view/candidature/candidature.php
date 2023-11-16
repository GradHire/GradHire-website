<?php
/** @var $candidatures \app\src\model\dataObject\Postuler[] */

/** @var $titre string */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\core\components\Table;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\UtilisateurRepository;

?>
<div class="flex flex-col gap-1 w-full pt-12 pb-24">
    <h2 class="font-bold text-lg"><?php echo $titre ?></h2>
    <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
        <div class="overflow-x-auto w-full">
            <?php
            Table::createTable($candidatures, ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature"], function ($candidature) {
                print_r($candidature);
                $offre = (new OffresRepository())->getById($candidature->getIdOffre());
                $entreprise = (new UtilisateurRepository([]))->getUserById($candidature->getIdEntreprise());
                $etudiant = (new UtilisateurRepository([]))->getUserById($candidature->getIdUtilisateur());
                if (Auth::has_role(Roles::Teacher, Roles::Student, Roles::Staff, Roles::Manager,Roles::Enterprise)) {
                    if (Auth::has_role(Roles::Teacher) && $candidature->getStatut() != "en attente tuteur") return;
                    Table::cell($entreprise->getNomutilisateur());
                    Table::cell($offre->getSujet());
                    Table::cell($etudiant->getEmailutilisateur());
                    Table::cell($candidature->getDates());
                    if ($candidature->getStatut() == 'en attente entreprise') {
                        Table::chip("En attente entreprise", "yellow");
                    } elseif ($candidature->getStatut() == 'en attente etudiant') {
                        Table::chip("En attente etudiant", "yellow");
                    } elseif ($candidature->getStatut() == 'en attente tuteur') {
                        Table::chip("En attente tuteur", "yellow");
                    } elseif ($candidature->getStatut() == 'refusee') {
                        Table::chip("Refusé", "red");
                    } else Table::chip("Accepté", "green");
                    Table::button("/candidatures/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Voir plus");
                    if (Auth::has_role(Roles::Enterprise)){
                        if ($candidature->getStatut() == "en attente entreprise") {
                            Table::button("/candidatures/validerEntreprise/" . $candidature->getIdUtilisateur() ."/". $candidature->getIdOffre(), "Valider");
                            Table::button("/candidatures/refuser/" . $candidature->getIdUtilisateur() ."/". $candidature->getIdOffre(), "Refuser");
                        }
                    }
                    if (Auth::has_role(Roles::Student)){
                        if ($candidature->getStatut() == "en attente etudiant") {
                            Table::button("/candidatures/validerEtudiant/" . $candidature->getIdUtilisateur() ."/". $candidature->getIdOffre(), "Accpeté");
                            Table::button("/candidatures/refuser/" . $candidature->getIdUtilisateur() ."/". $candidature->getIdOffre(), "Refuser");
                        }
                    }
                    else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance) && $candidature->getStatut() == 'en attente tuteur' && $candidature->getSiTuteurPostuler()) {
                        Table::button("/postuler/listeTuteur/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Voir Liste Tuteur");
                    }
                    if (Auth::has_role(Roles::Teacher) && $candidature->getStatut() == 'en attente tuteur' && !$candidature->getIfSuivi(Auth::get_user()->id()) && (new StaffRepository([]))->getCountPostulationTuteur(Auth::get_user()->id()) < 10) {
                        Table::button("/postuler/seProposer/" . $candidature->getIdOffre(), "Se proposer comme tuteur");
                    } else if (Auth::has_role(Roles::Teacher) && $candidature->getStatut() == 'en attente tuteur' && (new StaffRepository([]))->getCountPostulationTuteur(Auth::get_user()->id()) < 10 && $candidature->getIfSuivi(Auth::get_user()->id())) {
                        Table::button("/postuler/seDeproposer/" . $candidature->getIdOffre(), "X");
                    }
                }
            });
            ?>
        </div>
    </div>
</div>