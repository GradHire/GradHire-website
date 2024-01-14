<?php
/** @var $candidatures Postuler[] */

/** @var $titre string */

use app\src\model\Auth;
use app\src\model\dataObject\Postuler;
use app\src\model\dataObject\Roles;
use app\src\model\repository\CacheRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\TuteurRepository;
use app\src\view\components\ui\Table;

?>
<div class="flex flex-col w-full gap-4">
    <h2 class="font-bold text-lg"><?php echo $titre ?></h2>
    <div class=" w-full">
        <?php
        $getifValideeInArray = PostulerRepository::getIfValideeInArray($candidatures);
        if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) || !$getifValideeInArray) $nameColonnes = ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature"];
        else if ($getifValideeInArray) $nameColonnes = ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature", "Tuteur"];
        Table::createTable($candidatures, $nameColonnes, function ($candidature) {
            if (Auth::has_role(Roles::Teacher, Roles::Student, Roles::Staff, Roles::Manager, Roles::Enterprise, Roles::Tutor, Roles::TutorTeacher)) {
                if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && !($candidature['statut'] == "en attente tuteur prof" || $candidature['statut'] == "en attente responsable" || $candidature['statut'] == "validee" || $candidature['statut'] == "refusee" || $candidature['statut'] == "en attente tuteur entreprise")) {
                    print "l'etat de la candidature ne permet pas de voir plus d'information";
                    return;
                }
                Table::cell($candidature['nomentreprise']);
                Table::cell($candidature['sujet']);
                Table::cell($candidature['emailetudiant']);
                Table::cell($candidature['dates']);

                switch ($candidature['statut']) {
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
                        $tuteursEntreprise = CacheRepository::getTutorsByEntreprise(Auth::get_user()->Id());
                        $tuteurProf = (new TuteurRepository([]))->getTuteurByIdEtudiantAndIdOffre($candidature['idutilisateur'], $candidature['idoffre']);
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
                } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerStage, Roles::ManagerAlternance) && $candidature['statut'] == 'en attente responsable' && (new PostulerRepository())->getSiTuteurPostuler($candidature['idutilisateur'], $candidature['idoffre'])) {
                    Table::button("/postuler/listeTuteur/" . $candidature['idoffre'] . "/" . $candidature['idutilisateur'], "Voir Liste Tuteur");
                } else if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && CacheRepository::getPostulationsCount(Auth::get_user()->id()) < 10) {
                    if (!(new PostulerRepository())->getIfSuivi(Auth::get_user()->id(), $candidature["idutilisateur"], $candidature['idoffre'])) {
                        Table::button("/postuler/seProposer/" . $candidature['idoffre'] . "/" . $candidature['idutilisateur'], "Se proposer comme tuteur");
                    } else if ($candidature['statut'] == 'en attente responsable' || $candidature['statut'] == 'en attente tuteur entreprise') {
                        Table::button("/postuler/seDeproposer/" . $candidature['idoffre'], "X");
                    }
                }
                if (!Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher) && !empty($candidature["idtutor"])) {
                    $tuteur = CacheRepository::getTutorById($candidature["idtutor"]);
                    Table::cell($tuteur);
                }
                Table::button("/candidatures/" . $candidature['idoffre'] . "/" . $candidature['idutilisateur'], "Voir plus");
            }
        });
        ?>
    </div>
</div>