<?php
/** @var $utilisateurs \app\src\model\dataObject\Utilisateur */

use app\src\core\exception\ForbiddenException;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

$this->title = 'Utilisateurs';

$staffRoles = [Roles::Staff, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Teacher];

?>
<?php
if (Auth::has_role(Roles::Staff, Roles::Manager, Roles::ChefDepartment)) {
    ?>
    <div class="overflow-x-auto w-full pt-12 pb-24">
        <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
            <thead class="ltr:text-left rtl:text-right">
            <tr>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Nom d'utilisateur
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Email
                </th>
                <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                    Téléphone
                </th>
            </tr>
            </thead>

            <tbody class="divide-y divide-zinc-200">
            <?php foreach ($utilisateurs as $utlisateur) { ?>
                <tr class="odd:bg-zinc-50">
                    <td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
                        <?= $utlisateur->getNomutilisateur(); ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?php
                        $email = $utlisateur->getEmailutilisateur();
                        if ($email != null) echo $email;
                        else echo("Non renseigné");
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                        <?php
                        if ($utlisateur->getNumtelutilisateur() == null) echo("Non renseigné");
                        else echo $utlisateur->getNumtelutilisateur();
                        ?>
                    </td>
                    <td class="whitespace-nowrap px-4 py-2">
                        <?php if (Auth::has_role(Roles::ChefDepartment)): ?>
                            <form action="/utilisateurs/<?= $utlisateur->getIdutilisateur(); ?>/role"
                                  method="post">
                                <select name="role" id="role"
                                        class="border-gray-600 border-2 text-zinc-700 rounded-lg sm:text-sm px-2 py-1 cursor-pointer">
                                    <?php foreach ($staffRoles as $role) { ?>
                                        <option value="<?= $role->value; ?>"
                                            <?= $utlisateur->getRole() == $role->value ? "selected" : ""; ?>>
                                            <?= $role->value; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <button type="submit"
                                        class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">
                                    Appliquer
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="/utilisateurs/<?= $utlisateur->getIdutilisateur(); ?>"
                               class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                plus</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>
    <?php
} else {
    throw new ForbiddenException();
}