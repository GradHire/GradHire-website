<?php

use app\src\model\repository\EntrepriseRepository;
use app\src\view\components\ui\Table;

$entreprises = new EntrepriseRepository([]);
$entreprises = $entreprises->getAll();
?>
<div class="flex pt-12">
    <p> L'entreprise n'existe pas encore ?</p>
    <a href="/creerEntreprise"
       class="ml-3 inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Créer-la</a>
</div>
<div class=" overflow-x-auto w-full example  pb-24">
    <?php
    Table::createTable($entreprises, ["Nom d'entreprise", "Numéro Siret", "Adresse", "Code Postal", "Commune", "Pays"], function ($entreprise) {
        Table::cell($entreprise->getNom());
        Table::cell($entreprise->getSiret());
        Table::cell($entreprise->getAdresse());
        Table::cell($entreprise->getCodePostal());
        Table::cell($entreprise->getVille());
        Table::cell($entreprise->getPays());
        Table::button('/previewOffre?idEntreprise=' . $entreprise->getIdutilisateur(), "Choisir");
    });
    ?>
</div>
