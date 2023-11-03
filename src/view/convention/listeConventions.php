<?php
/** @var $conventions ConventionRepository */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;

$this->title = 'Conventions';

?>
<div class="overflow-x-auto w-full pt-12 pb-24">
    <table class="min-w-full divide-y-2 divide-zinc-200 bg-white text-sm">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Origine Convention
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                IdUtilisateur
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                IdOffre
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php
        if ($conventions != null) {
            foreach ($conventions as $convention) { ?>
                <?php
                if (Auth::get_user()->role() == Roles::Enterprise && (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur() != Auth::get_user()->id) continue;
                elseif (Auth::get_user()->role() == Roles::Student && $convention->getIdUtilisateur() != Auth::get_user()->id) continue;
                else {
                    ?>
                    <tr class="odd:bg-zinc-50">
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            $ogc = $convention->getOrigineConvention();
                            if ($ogc != null) echo $ogc;
                            else echo("Non renseigné");
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($convention->getIdUtilisateur() == null) echo("Non renseigné");
                            else echo $convention->getIdUtilisateur();
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($convention->getIdOffre() == null) echo("Non renseigné");
                            else echo $convention->getIdOffre(); ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="/conventions/<?= $convention->getNumConvention(); ?>"
                               class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                plus</a>
                        </td>
                    </tr>
                <?php }



            }
        } ?>
        </tbody>

    </table>
</div>