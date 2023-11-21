<?php

use app\src\core\components\Lien;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$this->title = 'Dashboard';

/** @var array $data
 * @var string $currentTab
 */

//echo '<pre>';
//print_r($data);
//echo '</pre>';

$linkOffres = new Lien('/offres', 'Offres');
$linkUtilisateurs = new Lien('/utilisateurs', 'Gestion roles');
$linkEntreprises = new Lien('/entreprises', 'Entreprises');
$linkCandidatures = new Lien('/candidatures', 'Candidatures');
$linkCreate = new Lien('/offres/create', 'Créer une offre');
$linkTuteurs = new Lien('/ListeTuteurPro', 'Tuteurs');
$linkImport = new Lien('/importer', 'Import');
$linkImportStudea = new Lien('/importerStudea', 'Import Studea');
$linkConventions = new Lien('/conventions', 'Conventions');
$linkExplicationSimu = new Lien('/explicationSimu', 'Simulateur');

?>
<div class="w-full flex md:flex-row flex-col justify-between items-start gap-4 mx-auto pt-12 pb-24">
    <div class="flex flex-col gap-4 w-full md:max-w-[400px]">
        <?php require __DIR__ . '/tabs.php'; ?>
        <div class="relative w-full bg-zinc-50 isolate overflow-hidden border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-2 md:p-4 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
            <?php if ($currentTab === 'tab1') { ?>
                <div class="flex flex-col gap-2 items-start justify-start">

                </div>
            <?php } elseif ($currentTab === 'tab2') { ?>
                <div class="flex flex-col gap-2 items-start justify-start">
                    <div class="flex flex-col w-full gap-2 items-center justify-center">
                        <?php
                        if (!Application::isGuest()) {
                            if (!Auth::has_role(Roles::ChefDepartment)) echo $linkOffres->render();
                            else echo $linkUtilisateurs->render();
                            if (!Auth::has_role(Roles::Enterprise, Roles::Tutor, Roles::ChefDepartment)) echo $linkEntreprises->render();
                            if (Auth::has_role(Roles::Student, Roles::Teacher, Roles::Tutor, Roles::Enterprise, Roles::TutorTeacher)) echo $linkCandidatures->render();
                            if (Auth::has_role(Roles::Enterprise)) {
                                echo $linkCreate->render();
                                echo $linkTuteurs->render();
                            }
                            if (Auth::has_role(Roles::Manager, Roles::Staff)) {
                                echo $linkUtilisateurs->render();
                                echo $linkCandidatures->render();
                                echo $linkTuteurs->render();
                                echo $linkImport->render();
                                echo $linkImportStudea->render();
                            }
                            if (Auth::has_role(Roles::Student)) echo $linkExplicationSimu->render();
                            if (Auth::has_role(Roles::Enterprise, Roles::Student, Roles::Manager, Roles::Staff)) echo $linkConventions->render();
                        }
                        ?>
                    </div>
                </div>
            <?php } elseif ($currentTab === 'tab3') { ?>
                <div class="flex flex-col gap-2 items-start justify-start">

                </div>
            <?php } ?>
        </div>
    </div>
    <div class="relative grow isolate overflow-hidden w-full gap-4 flex flex-col bg-zinc-50 border rounded-2xl text-[#1A2421] backdrop-blur-xl [ p-8 md:p-10 lg:p-10 ] [ border-[1px] border-solid border-black  border-opacity-10 ] [ shadow-black/5 shadow-2xl ]">
        <?php if ($currentTab === 'tab1') { ?>
            <div class='flex flex-col gap-2 items-start justify-start'>

            </div>
        <?php } elseif ($currentTab === 'tab2') { ?>
            <div class='flex flex-col gap-2 items-start justify-start'>

            </div>
        <?php } elseif ($currentTab === 'tab3') { ?>
            <div class='flex flex-col gap-2 items-start justify-start'>

            </div>
        <?php } ?>
    </div>
</div>