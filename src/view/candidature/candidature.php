<?php
/** @var $candidatures \app\src\model\dataObject\Postuler[] */
/** @var $titre string */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\view\components\ui\Table;

?>
<div class="flex flex-col gap-1 w-full gap-4 mx-auto">
    <h2 class="font-bold text-lg"><?php echo $titre ?></h2>
    <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
        <div class="overflow-x-auto w-full">
            <?php
            $getifValideeInArray = PostulerRepository::getIfValideeInArray($candidatures);
            if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) || !$getifValideeInArray) {
                $nameColonnes = ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature"];
            } else if ($getifValideeInArray){
                $nameColonnes = ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature", "Tuteur"];
            }
            Table::createTable($candidatures, $nameColonnes, function ($candidature) {
                $postulerRepository = new PostulerRepository();
                $staffRepository = new StaffRepository([]);
                $tuteurRepository = new TuteurRepository([]);
                $offre = (new OffresRepository())->getById($candidature['idoffre']);
                $entreprise = (new UtilisateurRepository([]))->getUserById($candidature['identreprise']);
                $etudiant = (new UtilisateurRepository([]))->getUserById($candidature['idutilisateur']);
                if (Auth::has_role(Roles::Teacher, Roles::Student, Roles::Staff, Roles::Manager, Roles::Enterprise, Roles::Tutor, Roles::TutorTeacher)) {
                    if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && !($candidature['statut'] == "en attente tuteur prof" || $candidature['statut'] == "en attente responsable" || $candidature['statut'] == "validee" || $candidature['statut'] == "refusee" || $candidature['statut'] == "en attente tuteur entreprise")) {
                        print "l'etat de la candidature ne permet pas de voir plus d'information";
                        return;
                    }
                    Table::cell($entreprise->getNom());
                    Table::cell($offre->getSujet());
                    Table::cell($etudiant->getEmail());
                    Table::cell($candidature['dates']);

                    switch($candidature['statut']){
                        case "en attente entreprise":
                            Table::chip("En attente entreprise", "yellow");
                            break;
                        case "en attente etudiant":
                            Table::chip("En attente etudiant", "yellow");
                            break;
                        case "en attente tuteur prof":
                            Table::chip("En attente tuteur prof", "yellow");
                            break;
                        case "en attente tuteur entreprise":
                            Table::chip("En attente tuteur entreprise", "yellow");
                            break;
                        case "en attente responsable":
                            Table::chip("En attente responsable", "yellow");
                            break;
                        case "refusee":
                            Table::chip("Refusé", "red");
                            break;
                        case "validee":
                            Table::chip("Accepté", "green");
                            break;
                    }

                    if (Auth::has_role(Roles::Enterprise)) {
                        if ($candidature['statut'] == "en attente entreprise") {
                            Table::button("/candidatures/validerEntreprise/" . $candidature['idutilisateur'] . "/" . $candidature['idoffre'], "Valider");
                            Table::button("/candidatures/refuser/" . $candidature['idutilisateur'] . "/" . $candidature['idoffre'], "Refuser");
                        } else if ($candidature['statut'] == "en attente tuteur entreprise") {
                            $tuteursEntreprise = $tuteurRepository->getAllTuteursByIdEntreprise(Auth::get_user()->Id());
                            $tuteurProf = $tuteurRepository->getTuteurByIdEtudiantAndIdOffre($candidature['idutilisateur'], $candidature['idoffre']);
                            if (!empty($tuteursEntreprise)) {
                                $options = "";
                                foreach ($tuteursEntreprise as $tuteur) {
                                    $options .= "<option value=" . $tuteur->getIdutilisateur() . ">" . $tuteur->getPrenom() . "</option>";
                                }
                                Table::cell('
                               <form action="/postuler/assignerCommeTuteur/' . $candidature['idoffre'] . "/" . $tuteurProf['idutilisateur'] . '/' . $candidature['idutilisateur'] . '"
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
                        if ($candidature['statut'] == "en attente etudiant") {
                            Table::button("/candidatures/validerEtudiant/" . $candidature['idutilisateur'] . "/" . $candidature['idoffre'], "Accpeté");
                            Table::button("/candidatures/refuser/" . $candidature['idutilisateur'] . "/" . $candidature['idoffre'], "Refuser");
                        }
                    } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance) && $candidature['statut'] == 'en attente responsable' && $postulerRepository->getSiTuteurPostuler($candidature['idutilisateur'], $candidature['idoffre'])) {
                        Table::button("/postuler/listeTuteur/" . $candidature['idoffre'] . "/" . $candidature['idutilisateur'], "Voir Liste Tuteur");
                    }
                    else if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && $staffRepository->getCountPostulationTuteur(Auth::get_user()->id()) < 10) {
                        if (!$postulerRepository->getIfSuivi(Auth::get_user()->id(), $etudiant->getIdutilisateur(), $candidature['idoffre'])) {
                            Table::button("/postuler/seProposer/" . $candidature['idoffre'] . "/" . $candidature['idutilisateur'], "Se proposer comme tuteur");
                        } else if ($candidature['statut'] == 'en attente responsable' || $candidature['statut'] == 'en attente tuteur entreprise') {
                            Table::button("/postuler/seDeproposer/" . $candidature['idoffre'], "X");
                        }
                    }
                    if ($postulerRepository->getTuteurByIdOffre($candidature['idoffre']) && !Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher)) {
                        $tuteur = $tuteurRepository->getNomTuteurByIdEtudiantAndIdOffre($candidature['idutilisateur'], $candidature['idoffre']);
                        Table::cell($tuteur);
                    }
                    Table::button("/candidatures/" . $candidature['idoffre'] . "/" . $candidature['idutilisateur'], "Voir plus");
                }
            });
            ?>
        </div>
    </div>
</div>