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
                $offre = (new OffresRepository())->getById($candidature->getIdOffre());
                $entreprise = (new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur());
                $etudiant = (new UtilisateurRepository([]))->getUserById($candidature->getIdUtilisateur());
                if (Auth::has_role(Roles::Teacher, Roles::Student) && $candidature->getStatut() == 'valider' || Auth::has_role(Roles::Staff, Roles::Manager)) {
                    Table::cell($entreprise->getNomutilisateur());
                    Table::cell($offre->getSujet());
                    Table::cell($etudiant->getEmailutilisateur());
                    Table::cell($candidature->getDates());
                    if ($candidature->getStatut() == 'en attente') {
                        Table::chip("En attente", "yellow");
                    } elseif ($candidature->getStatut() == 'refuser') {
                        Table::chip("Refusé", "red");
                    } else Table::chip("Accepté", "green");
                    Table::button("/candidatures/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Voir plus");

                    if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance) && $candidature->getStatut() == 'valider' && $candidature->getSiTuteurPostuler()) {
                        Table::button("/postuler/listeTuteur/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Voir Liste Tuteur");
                    }
                    if (Auth::has_role(Roles::Teacher) && $candidature->getStatut() == 'valider' && !$candidature->getIfSuivi(Auth::get_user()->id()) && (new StaffRepository([]))->getCountPostulationTuteur(Auth::get_user()->id()) < 10) {
                        Table::button("/postuler/seProposer/" . $candidature->getIdOffre(), "Se proposer comme tuteur");
                    } else if (Auth::has_role(Roles::Teacher) && $candidature->getStatut() == 'valider' && (new StaffRepository([]))->getCountPostulationTuteur(Auth::get_user()->id()) < 10 && $candidature->getIfSuivi(Auth::get_user()->id())) {
                        Table::button("/postuler/seDeproposer/" . $candidature->getIdOffre(), "X");
                    }
                }
            });
            ?>
        </div>
    </div>
</div>