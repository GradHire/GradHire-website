<?php

use app\src\model\dataObject\Etudiant;

/** @var $etudiants Etudiant */

use app\src\core\components\Table;

?>
<div class="overflow-x-auto w-full pb-24">
    <div>
        <a href="/harceler?isAdmin=14"
           class="ml-3 inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Harceler
            tout les étudiants</a>
    </div>
    <?php
    Table::createTable($etudiants, ["Nom", "Prénom", "email", "NumEtudiant"], function ($etudiant) {
        Table::cell($etudiant->getNom());
        Table::cell($etudiant->getPrenom());
        Table::cell($etudiant->getEmail());
        Table::cell($etudiant->getNumEtudiant());
        Table::button('/harceler?idUtilisateur=' . $etudiant->getIdutilisateur(), "Harceler");
    });
    ?>
</div>
