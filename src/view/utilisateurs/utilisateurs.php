<?php
/** @var $utilisateurs \app\src\model\dataObject\Utilisateur */

use app\src\core\components\Table;

$this->title = 'Utilisateurs';


?>
<?php
Table::createTable($utilisateurs, ["nom", "email", "numéro de téléphone"], function ($utilisateur) {
    Table::cell($utilisateur->getNomutilisateur());
    Table::cell($utilisateur->getEmailutilisateur());
    Table::cell($utilisateur->getNumtelutilisateur());
    Table::button("/utilisateurs/" . $utilisateur->getIdutilisateur(), "Voir plus");
});
