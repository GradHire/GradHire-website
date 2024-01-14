<?php

/** @var $convention \app\src\model\dataObject\Convention */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\repository\VisiteRepository;

if (Auth::has_role(Roles::Enterprise) && Auth::get_user()->id() != (new OffresRepository())->getById($convention['idoffre'])->getIdutilisateur()) {
    throw new Exception("Vous n'avez pas le droit d'accéder à cette convention car elle n'appartient pas à votre entreprise.");
}

?>

<div class="w-full gap-4 mx-auto">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900">Convention numero
                : <?= $convention['numconvention'] ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-zinc-500">
                <?php
                try {
                    $date = new DateTime($convention['datecreation']);
                    echo "Publiée le " . $date->format('d/m/Y') . " à " . $date->format('H:i:s');
                } catch (Exception $e) {
                    echo "Something went wrong.";
                }
                ?></p>
        </div>
    </div>
    <div class="mt-6 border-t border-zinc-100">
        <dl class="divide-y divide-zinc-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Validiter Convention</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <div>
                        <?php
                        if ($convention['conventionvalidee'] == "0") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
Non valide
    </span>";
                        } else if ($convention['conventionvalidee'] == "1") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                        }
                        ?>
                    </div>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Validiter Pedagogique</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <div>
                        <?php
                        if ($convention['conventionvalideepedagogiquement'] == "0") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Non valide
    </span>";
                        } else if ($convention['conventionvalideepedagogiquement'] == "1") {
                            echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                        }
                        ?>
                    </div>
                </dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Origine Convention</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention['origineconvention'] ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Date Modification</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention['datemodification'] ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Date Creation</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention['datecreation'] ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">idSignatiare</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention['idsignataire'] ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">idIterruption</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?php
                    $idIterruption = $convention['idinterruption'];
                    if ($idIterruption != null) echo $idIterruption;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Etudiant</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><a
                            href="/entreprises/<?= $convention['idutilisateur'] ?>"><?= (new EntrepriseRepository([]))->getUserById($convention['idutilisateur'])->getNom() ?></a>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">idOffre</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $convention['idoffre'] ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Commentaire</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?php
                    $commentaire = $convention['commentaire'];
                    if ($commentaire != null) echo $commentaire;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Statut</dt>
                <?php
                $visiterepo = VisiteRepository::getIfVisiteExist($convention['numconvention']);
                $imone = ConventionRepository::imOneOfTheTutor(Auth::get_user()->id(), $convention['numconvention']);
                if (!$visiterepo && (Auth::has_role(Roles::Student, Roles::Enterprise, Roles::Teacher) || (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && !$imone))) {
                    ?>
                    <dd class="text font-medium inline-flex justify-center items-center px-3 py-0.5 rounded-full text-sm leading-5 bg-amber-200 text-amber-800">
                        En attente de visite
                    </dd>
                    <?php
                } else if ($visiterepo && Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && !$imone) {
                    ?>

                    <dd class="text font-medium inline-flex items-center justify-center px-3 py-0.5 rounded-full text-sm leading-5 bg-red-200 text-red-800">
                        Vous n'avez pas accès à cette visite
                    </dd>
                    <?php
                } else {
                    ?>
                    <a href="/visite/<?php echo $convention['numconvention'] ?>"
                       class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir
                        la
                        date de
                        visite</a>
                <?php } ?>
            </div>
        </dl>

        <div class="flex justify-center mt-8">
            <?php
            $getIfSoutenanceExist = SoutenanceRepository::getIfSoutenanceExist($convention['numconvention']);
            if (Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Staff)) {
                if ($convention['conventionvalidee'] == "1" && $convention['conventionvalideepedagogiquement'] == "1" && !$getIfSoutenanceExist) {
                    //Table::button("/createSoutenance/" . $getNumConvention, "Creer soutenance");
                    ?> <a href="/createSoutenance/<?php echo $convention['numconvention'] ?>"
                          class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Créer
                        soutenance</a> <?php
                } else {
                    //Table::button("/voirSoutenance/" . $getNumConvention, "Voir soutenance");
                    ?> <a href="/voirSoutenance/<?php echo $convention['numconvention'] ?>"
                          class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir
                        soutenance</a> <?php }
            }
            $imOneOfTheTutor = ConventionRepository::imOneOfTheTutor(Auth::get_user()->id, $convention['numconvention']);
            if (!$getIfSoutenanceExist) {
                if (Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && $convention['conventionvalidee'] == "1" && $convention['conventionvalideepedagogiquement'] == "1") {
                    ?><?php } else if (!Auth::has_role(Roles::ManagerAlternance, Roles::Manager, Roles::ManagerStage, Roles::Staff)) {
                    ?> <p>en attente de la validation </p> <?php
                }
            } else if (VisiteRepository::getIfVisiteExist($convention['numconvention']) && !$getIfSoutenanceExist) {
                ?> <p>en attente de la soutenance </p> <?php
            } else {
                if (Auth::has_role(Roles::TutorTeacher, Roles::Teacher) && !SoutenanceRepository::getIfJuryExist(Auth::get_user()->id, $convention['numconvention'])) {
                    if (!SoutenanceRepository::getIfImTheTuteurProf(Auth::get_user()->id, $convention['numconvention'])) {
                        ?> <a href="/seProposerJury/<?php echo $convention['numconvention'] ?>"
                              class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Etre
                            jury</a> <?php
                    }
                } else if (Auth::has_role(Roles::TutorTeacher) && (SoutenanceRepository::imTheJury(Auth::get_user()->id, $convention['numconvention']) || SoutenanceRepository::imTheTuteurProf(Auth::get_user()->id, $convention['numconvention']))) {
                    ?> <a href="/voirSoutenance/<?php echo $convention['numconvention'] ?>"
                          class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir
                        soutenance</a> <?php
                } else if (Auth::has_role(Roles::Tutor) && SoutenanceRepository::imTheTuteurEntreprise(Auth::get_user()->id, $convention['numconvention'])) {
                    ?> <a href="/voirSoutenance/<?php echo $convention['numconvention'] ?>"
                          class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir
                        soutenance</a> <?php
                } else if (Auth::has_role(Roles::Student) && SoutenanceRepository::imTheEtudiant(Auth::get_user()->id, $convention['numconvention'])) {
                    ?> <a href="/voirSoutenance/<?php echo $convention['numconvention'] ?>"
                          class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir
                        soutenance</a> <?php
                } else if (!Auth::has_role(Roles::ManagerAlternance, Roles::Manager, Roles::ManagerStage, Roles::Staff)) {
                    ?> <p>Vous n'êtes pas concerné</p> <?php
                }
            }
            ?>
        </div>
    </div>
</div>
