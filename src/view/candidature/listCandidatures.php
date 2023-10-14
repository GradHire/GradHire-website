<?php
/** @var $candidaturesAttente \app\src\model\dataObject\Candidature */
/** @var $candidaturesAutres \app\src\model\dataObject\Candidature */

use app\src\model\Auth\Auth;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;

?>

<form method="GET" action="offres" class="w-full gap-4 flex flex-col pt-12 pb-24">
    <div class="w-full grid-cols-1 gap-4 lg:grid-cols-4 ">
        <div class="w-full lg:col-span-3 rounded-lg flex flex-col gap-4">

            <?php if(!empty($candidaturesAttente)){
                $candidatures=$candidaturesAttente;
                $titre='Candidatures en Attente';
                require __DIR__ .'/candidature.php';
            }
            ?>

            <div class="w-full bg-zinc-200 h-[1px] rounded-full"></div>

            <?php if(!empty($candidaturesAutres)){
                $candidatures=$candidaturesAutres;
                $titre='Candidatures Traitées';
                require __DIR__ .'/candidature.php';
            }

            if(empty($candidaturesAttente) && empty($candidaturesAutres)){
                echo '<h2> Aucune Candidature </h2>';
            }
            ?>

    </div>
    </div>
</form>
