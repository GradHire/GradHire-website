<?php
/** @var $offres \app\src\model\dataObject\Offre */

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">
    <?php
    require_once __DIR__ . '/search.php';

    ?>
    <div class="flex flex-wrap gap-4 justify-start">
        <?php


        if ($offres != null) {
            foreach ($offres as $offre) {
                require __DIR__ . '/offre.php';
            }
        } else {
            require __DIR__ . '/errorOffre.php';
        }
        ?>
    </div>
</div>
