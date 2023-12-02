<?php
/** @var $entreprises Entreprise[] */

use app\src\core\components\Table;
use app\src\model\dataObject\Entreprise;

$this->title = 'Entreprises';

?>
<div class="overflow-x-auto w-full pt-12 pb-24">
    <?php
    Table::createTable($entreprises, ["Nom d'entreprise", "Email", "Téléphone", "Site web"], function ($entreprise) {
        Table::cell($entreprise->getNom());
        Table::mail($entreprise->getEmail());
        Table::phone($entreprise->getNumtelephone());
        Table::link($entreprise->getSiteWeb());
        Table::button("/entreprises/" . $entreprise->getIdutilisateur());
    });
    ?>
</div>