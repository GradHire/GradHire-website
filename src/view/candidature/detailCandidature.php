<?php

/** @var $candidatures \app\src\model\dataObject\Candidature */

use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\repository\OffresRepository;

$etudiant = (new EtudiantRepository())->getByIdFull($candidatures->getIdutilisateur());
$nometudiant = (new UtilisateurRepository())->getUserById($candidatures->getIdutilisateur())->getNomutilisateur();
$offre = (new OffresRepository())->getById($candidatures->getIdoffre());

echo '<h2 class="font-bold text-lg">Candidature de
'.$nometudiant. " " . $etudiant->getPrenomutilisateurldap() .'
</h2>';
?>

<div class="mt-6 border-t border-zinc-100">
    <dl class="divide-y divide-zinc-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Statut</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                <div>
                    <?php
                    if ($candidatures->getEtatcandidature() == "on hold") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
    </span>";
                    } else if ($candidatures->getEtatcandidature() == "accepted") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Acceptée
    </span>";
                    } else if ($candidatures->getEtatcandidature() == "declined") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
                    } else if ($candidatures->getEtatcandidature() == "draft") {
                        echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Archivée
    </span>";
                    }else{
                        echo $candidatures->getEtatcandidature();
                    }
                    ?>
                </div>
            </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Nom de l'offre</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0"><?= $offre->getSujet() ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">CV de l'étudiant</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                <?php
                $filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/cv.pdf";
                echo "<a href='".$filepath."' download target='_blank'>📥</a>";
                ?>
            </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-zinc-900">Lettre de motivation de l'étudiant</dt>
            <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                <?php
                $filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/ltm.pdf";
                echo "<a href='".$filepath."' download target='_blank'>📥</a>";
                ?>
            </dd>
        </div>
    </dl>
</div>


<?php
if($candidatures->getEtatcandidature() == "on hold"){
    echo "<div class='flex flex-col mb-3'>
    <form action='/candidatures' method='POST'>
    <input type='hidden' name='idcandidature' value='".$candidatures->getIdcandidature()."'>
    ";
     ?>

    <div class="flex flex-row gap-4 w-full">
        <input type="submit" name="action" value="Accepter"
               class="w-full text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
        <input onclick="saveForm()" type="submit" name="action" value="Refuser"
               class=" max-w-[150px] text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
    </div>

    <?php

    echo "</form>
    </div>";
}
?>

