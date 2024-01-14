<?php
/** @var $listTuteur ?array */

use app\src\view\components\ui\Table;


?>
<div class="flex w-full">
    <div class="flex flex-row items-center justify-between w-full"><p> Le tuteur n'existe pas encore ?</p><a href="/creerTuteur" class="ml-3 inline-block rounded bg-zinc-800 px-4 py-2 w-fit text-xs font-medium text-white hover:bg-zinc-900">Créer-le</a></div>
</div>
<div class=" overflow-x-auto w-full example  pb-24">
    <?php
    if ($listTuteur == null) {
        echo "<p> Aucun tuteur n'a été trouvé </p>";
        return;
    }
    Table::createTable($listTuteur, ["Nom", "Prénom", "Fonction", "Tel/Mail"], function ($tuteur) {
        Table::cell($tuteur->getNom());
        Table::cell($tuteur->getPrenom());
        Table::cell($tuteur->getFonction());
        Table::cell($tuteur->getNumtelephone());
        Table::button('/simulateurCandidature?idTuteur=' . $tuteur->getIdutilisateur(), "Choisir");
    });
    ?>
</div>
