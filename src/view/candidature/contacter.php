<?php

use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;

?>


<form action="/candidatures/contacter/<?= $identreprise ?>" method="post" class="w-full flex flex-col">
<div class="flex flex-row gap-4 w-full">
    <input hidden name="identreprise" value="<?= $identreprise?>">
    <input hidden name="idetudiant" value="<?= $idetudiant?>">
    <input hidden name="idoffre" value="<?= $idoffre ?>"/>
    <input hidden name="emailEntreprise" value="<?= (new UtilisateurRepository([]))->getUserById($identreprise)->getEmailutilisateur()?>"/>
    <input hidden name="emailEtudiant" value="<?= (new UtilisateurRepository([]))->getUserById($idetudiant)->getEmailutilisateur() ?>"/>
    <input hidden name="sujet" value=" Question : <?= (new OffresRepository())->getById($idoffre)->getSujet() ?>"/>
    <input type="text" name="message" class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
</div>
<div class="flex flex-row gap-4 w-full">
    <input type="submit" name="action" value="Envoyer"
           class="w-full text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"/>
</div>
</form>