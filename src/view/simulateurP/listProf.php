<?php
/** @var $listProf */

use app\src\view\components\ui\Table;

?>
<h1 class=" mx-auto max-w-md font-medium text-2xl ">Simulateur Pstage (Professeur Référent)</h1>
<div class="overflow-x-auto w-full pb-24">
    <?php
    Table::createTable($listProf, ["Nom", "Prénom", "Téléphone", "Mail"], function ($prof) {
        Table::cell($prof->getNom());
        Table::cell($prof->getPrenom());
        Table::cell($prof->getNumtelephone());
        Table::cell($prof->getEmail());
        Table::button('/simulateurSignataire?idProfRef=' . $prof->getIdutilisateur(), "Choisir");
    });
    ?>
</div>


