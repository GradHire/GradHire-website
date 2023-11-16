<?php
/** @var $utilisateurs \app\src\model\dataObject\Utilisateur */

use app\src\core\components\Table;
use app\src\core\exception\ForbiddenException;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$this->title = 'Utilisateurs';


?>
<?php
Table::createTable($utilisateurs, ["nom", "email", "numéro de téléphone"], function ($utilisateur) {
    Table::cell($utilisateur->getNomutilisateur());
    Table::cell($utilisateur->getEmailutilisateur());
    Table::cell($utilisateur->getNumtelutilisateur());
    Table::button("/utilisateurs/" . $utilisateur->getIdutilisateur(), "Voir plus");
});
