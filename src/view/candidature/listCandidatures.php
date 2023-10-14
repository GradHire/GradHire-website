<?php
/** @var $candidatures \app\src\model\dataObject\Candidature */

use app\src\model\Auth\Auth;

$this->title = 'Candidates';


global $etatCandidature;
?>
<form method="GET" action="offres" class="w-full gap-4 flex flex-col pt-12 pb-24 ">
    <div class="w-full grid-cols-1 gap-4 lg:grid-cols-4 ">
        <div class="w-full lg:col-span-3 rounded-lg flex flex-col gap-4">
            <div class="flex flex-col gap-1 w-full">
                <h2 class="font-bold text-lg">Candidature en Attente</h2>
                <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
                    <?php
                    if ($candidatures != null) {
                        $etatCandidature = "En attente";
                        require __DIR__ . '/candidature.php';
                    } else require __DIR__ . '/errorCandidatures.php';
                    ?>
                </div>
            </div>
            <?php
            echo '<div class="w-full bg-zinc-200 h-[1px] rounded-full"></div>';
            echo '<div class="flex flex-col gap-1 w-full">';
            echo '<h2 class="font-bold text-lg">Candidatures déja Vue</h2>';
            ?>
            <div class="w-full">
                <?php
                if ($candidatures != null) {
                    $etatCandidature = "Vue";
                    require __DIR__ . '/candidature.php';
                } else require __DIR__ . '/errorCandidatures.php';
                echo "</div>"; ?>
            </div>
        </div>
    </div>
</form>
