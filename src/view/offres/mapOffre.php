<?php
echo 'En cours de programmation...';
$count = 0;
foreach ($adresseList as $adresse){
    echo '<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">';
    echo '<dt class="text-sm font-medium leading-6 text-zinc-900">Marqueur'.$count++.'</dt>';
    echo '<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">'.$adresse.'</dd>';
    echo '</div>';
}
?>
