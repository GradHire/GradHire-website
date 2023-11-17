<?php
/** @var $utilisateurs \app\src\model\dataObject\Utilisateur */

use app\src\core\components\Table;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$this->title = 'Utilisateurs';


Table::createTable($utilisateurs, ["nom", "email", "numéro de téléphone"], function ($utilisateur) {
    $staffRoles = [Roles::Staff, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Teacher];
    Table::cell($utilisateur->getNomutilisateur());
    Table::cell($utilisateur->getEmailutilisateur());
    Table::cell($utilisateur->getNumtelutilisateur());
    if (Auth::has_role(Roles::ChefDepartment)) {
        $options = "";
        foreach ($staffRoles as $role) {
            $options .= "<option value='$role->value' " . ($utilisateur->getRole() == $role->value ? "selected" : "") . ">$role->value</option>";
        }
        Table::cell('<form action="/utilisateurs/' . $utilisateur->getIdutilisateur() . '/role"
              method="post">
            <select name="role" id="role"
                    class="border-gray-600 border-2 text-zinc-700 rounded-lg sm:text-sm px-2 py-1 cursor-pointer">
                ' . $options . '
            </select>
            <button type="submit"
                    class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">
                Appliquer
            </button>
        </form>'
        );
    } else {
        Table::button("/utilisateurs/" . $utilisateur->getIdutilisateur());
    }
});
