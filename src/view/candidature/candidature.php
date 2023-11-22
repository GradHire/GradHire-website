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
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;

?>
<div class="flex flex-col gap-1 w-full pt-12 pb-24">
    <h2 class="font-bold text-lg"><?php echo $titre ?></h2>
    <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
        <div class="overflow-x-auto w-full">
            <?php
            if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) || !(new PostulerRepository())->getIfValideeInArray($candidatures)) {
                $nameColonnes = ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature"];
            } else {
                $nameColonnes = ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature", "Tuteur"];
            }
            Table::createTable(
            /**
             *
             * @throws \app\src\core\exception\ServerErrorException
             */ $candidatures, $nameColonnes, function ($candidature) {
                $offre = (new OffresRepository())->getById($candidature->getIdOffre());
                $entreprise = (new UtilisateurRepository([]))->getUserById($candidature->getIdEntreprise());
                $etudiant = (new UtilisateurRepository([]))->getUserById($candidature->getIdUtilisateur());
                if (Auth::has_role(Roles::Teacher, Roles::Student, Roles::Staff, Roles::Manager, Roles::Enterprise, Roles::Tutor, Roles::TutorTeacher)) {
                    if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && !($candidature->getStatut() == "en attente tuteur prof" || $candidature->getStatut() == "en attente responsable" || $candidature->getStatut() == "validee" || $candidature->getStatut() == "refusee" || $candidature->getStatut() == "en attente tuteur entreprise")) {
                        print "l'etat de la candidature ne permet pas de voir plus d'information";
                        return;
                    }
                    Table::cell($entreprise->getNomutilisateur());
                    Table::cell($offre->getSujet());
                    Table::cell($etudiant->getEmailutilisateur());
                    Table::cell($candidature->getDates());
                    if ($candidature->getStatut() == 'en attente entreprise') {
                        Table::chip("En attente entreprise", "yellow");
                    } elseif ($candidature->getStatut() == 'en attente etudiant') {
                        Table::chip("En attente etudiant", "yellow");
                    } elseif ($candidature->getStatut() == 'en attente tuteur prof') {
                        Table::chip("En attente tuteur prof", "yellow");
                    } elseif ($candidature->getStatut() == 'en attente tuteur entreprise') {
                        Table::chip("En attente tuteur entreprise", "yellow");
                    } elseif ($candidature->getStatut() == 'en attente responsable') {
                        Table::chip("En attente responsable", "yellow");
                    } elseif ($candidature->getStatut() == 'refusee') {
                        Table::chip("Refusé", "red");
                    } elseif ($candidature->getStatut() == 'validee') {
                        Table::chip("Accepté", "green");
                    }
                    if (Auth::has_role(Roles::Enterprise)) {
                        if ($candidature->getStatut() == "en attente entreprise") {
                            Table::button("/candidatures/validerEntreprise/" . $candidature->getIdUtilisateur() . "/" . $candidature->getIdOffre(), "Valider");
                            Table::button("/candidatures/refuser/" . $candidature->getIdUtilisateur() . "/" . $candidature->getIdOffre(), "Refuser");
                        } else if ($candidature->getStatut() == "en attente tuteur entreprise") {
                            $tuteursEntreprise = (new TuteurEntrepriseRepository([]))->getAllTuteursByIdEntreprise(Auth::get_user()->Id());
                            $tuteurProf = (new TuteurRepository([]))->getTuteurByIdEtudiantAndIdOffre($candidature->getIdUtilisateur(), $candidature->getIdOffre());
                            if (!empty($tuteursEntreprise)) {
                                $options = "";
                                foreach ($tuteursEntreprise as $tuteur) {
                                    $options .= "<option value=".$tuteur->getIdutilisateur().">".$tuteur->getPrenom()."</option>";
                                }
                                Table::cell('
                               <form action="/postuler/assignerCommeTuteur/' . $candidature->getIdOffre() . "/" . $tuteurProf['idutilisateur'] . '/' . $candidature->getIdUtilisateur().'"
                method="post">
            <select name="idtuteur" id="idtuteur"
                    class="border-gray-600 border-2 text-zinc-700 rounded-lg sm:text-sm px-2 py-1 cursor-pointer">
                ' . $options . '
            </select>
            <button type="submit"
                    class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">
                Appliquer
            </button>
        </form>'
                                );
                            } else {
                                Table::cell("Aucun tuteur dispo");
                            }
                        }
                    }
                    if (Auth::has_role(Roles::Student)) {
                        if ($candidature->getStatut() == "en attente etudiant") {
                            Table::button("/candidatures/validerEtudiant/" . $candidature->getIdUtilisateur() . "/" . $candidature->getIdOffre(), "Accpeté");
                            Table::button("/candidatures/refuser/" . $candidature->getIdUtilisateur() . "/" . $candidature->getIdOffre(), "Refuser");
                        }
                    } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance) && $candidature->getStatut() == 'en attente responsable' && $candidature->getSiTuteurPostuler()) {
                        Table::button("/postuler/listeTuteur/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Voir Liste Tuteur");
                    } else if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && (new StaffRepository([]))->getCountPostulationTuteur(Auth::get_user()->id()) < 10) {
                        if (!$candidature->getIfSuivi(Auth::get_user()->id())) {
                            Table::button("/postuler/seProposer/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Se proposer comme tuteur");
                        } else if ($candidature->getStatut() == 'en attente responsable' || $candidature->getStatut() == 'en attente tuteur entreprise') {
                            Table::button("/postuler/seDeproposer/" . $candidature->getIdOffre(), "X");
                        } else {
                            if (!Auth::has_role(Roles::Tutor, Roles::Teacher, Roles::TutorTeacher)) {
                                $tuteur = (new TuteurRepository([]))->getNomTuteurByIdEtudiantAndIdOffre($candidature->getIdUtilisateur(), $candidature->getIdOffre());
                                Table::cell($tuteur);
                            }
                        }
                    }
                    Table::button("/candidatures/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur(), "Voir plus");
                }
            });
            ?>
        </div>
    </div>
</div>