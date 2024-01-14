<?php
/** @var $entreprises Entreprise[] */

use app\src\core\exception\ForbiddenException;
use app\src\model\Auth;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Roles;
use app\src\model\View;
use app\src\view\components\ui\Table;

View::setCurrentSection('Entreprises');
$this->title = 'Entreprises';
if (Auth::has_role(Roles::Enterprise)) {
    throw new ForbiddenException("Vous n'avez pas le droit de voir cette page");
}
?>
<div class=" overflow-x-auto w-full example  gap-4 mx-auto">
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