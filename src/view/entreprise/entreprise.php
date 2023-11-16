<?php
/** @var $entreprises Entreprise[] */

use app\src\core\components\Table;
use app\src\model\dataObject\Entreprise;

$this->title = 'Entreprises';

?>
<div class="overflow-x-auto w-full pt-12 pb-24">
    <?php
    Table::createTable($entreprises, ["Nom d'entreprise", "Email", "Téléphone", "Site web"], function ($entreprise) {
        Table::cell($entreprise->getNomutilisateur());
        Table::mail($entreprise->getEmailutilisateur());
        Table::phone($entreprise->getNumtelutilisateur());
        Table::link($entreprise->getSiteWeb());
        Table::button("/entreprises/" . $entreprise->getIdutilisateur());
    });
    ?>
</div>