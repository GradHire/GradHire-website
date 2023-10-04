<?php
/** @var $offres \app\src\model\DataObject\Offres */

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">
    <?php
    require_once __DIR__ . '/search.php';

    ?>
    <div class="flex flex-wrap gap-4 justify-start">
        <?php


        foreach ($offres as $offre) {
            require __DIR__ . '/offre.php';
        }
        ?>
    </div>
</div>
