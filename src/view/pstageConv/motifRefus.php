<?php
/** @var $motif string */

?>

<div class="flex flex-col justify-center items-center mx-auto max-w-md">
    <h1 class="text-2xl mb-2 ">Motif de refus</h1>
    <p><?php echo $motif ?></p>
</div>

<button class="bg-zinc-600 hover:bg-zinc-700 inline-block rounded  px-4 py-2 text-xs font-medium text-white"
        onclick="window.history.back()">Retour
</button>
