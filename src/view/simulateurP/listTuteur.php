<?php
/** @var $listTuteur ?array */

use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\view\components\ui\Table;


?>
<div class="flex pt-12">
    <p> Le tuteur n'existe pas encore ?</p>
    <a href="/creerTuteur"
       class="ml-3 inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Créer-le</a>
</div>
<div class="overflow-x-auto w-full pb-24">
    <?php
    Table::createTable($listTuteur, ["Nom", "Prénom", "Fonction", "Tel/Mail"], function ($tuteur) {
        Table::cell($tuteur->getNom());
        Table::cell($tuteur->getPrenom());
        Table::cell($tuteur->getFonction());
        Table::cell($tuteur->getNumtelephone());
        Table::button('/simulateurCandidature?idTuteur=' . $tuteur->getIdutilisateur(), "Choisir");
    });
    ?>
</div>
