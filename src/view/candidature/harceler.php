<?php

use app\src\model\dataObject\Etudiant;
use app\src\view\components\ui\Table;

/** @var $etudiants Etudiant */

?>
<div class=" overflow-x-auto w-full example">
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
