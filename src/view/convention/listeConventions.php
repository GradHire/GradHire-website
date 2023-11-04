<?php
/** @var $conventions ConventionRepository */

use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\UtilisateurRepository;

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
                Etudiant
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                IdOffre
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Validité Entreprise
            </th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
                Validité Pédagogique
            </th>
        </tr>
        </thead>

        <tbody class="divide-y divide-zinc-200">
        <?php
        if ($conventions != null) {
            $count = 0;
            foreach ($conventions as $convention) { ?>
        <?php
                if (Auth::get_user()->role() == Roles::Enterprise && (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur() != Auth::get_user()->id) continue;
                elseif (Auth::get_user()->role() == Roles::Enterprise && !(new ConventionRepository())->checkIfConventionsValideePedagogiquement($convention->getNumConvention())) continue;
                elseif (Auth::get_user()->role() == Roles::Student && $convention->getIdUtilisateur() != Auth::get_user()->id && !(new ConventionRepository())->checkIfConventionsValideePedagogiquement($convention->getNumConvention())) continue;
                else {
                    $count++;
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
                            else echo (new EtudiantRepository([]))->getByIdFull($convention->getIdUtilisateur())->getPrenom() . " " . (new EtudiantRepository([]))->getByIdFull($convention->getIdUtilisateur())->getNomutilisateur();
                            ?>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-zinc-700">
                            <?php
                            if ($convention->getIdOffre() == null) echo("Non renseigné");
                            else echo (new OffresRepository())->getById($convention->getIdOffre())->getSujet()?>
                        </td>
                        <td class="mt-6 border-t border-zinc-100">
                                        <div>
                                            <?php
                                            if ($convention->getConventionValide() == "0") {
                                                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
Non valide
    </span>";
                                            } else if ($convention->getConventionValide() == "1") {
                                                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                                            }
                                            ?>
                                        </div>
                        </td>
                        <td>
                                        <div>
                                            <?php
                                            if ($convention->getConvetionValideePedagogiquement() == "0") {
                                                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Non valide
    </span>";
                                            } else if ($convention->getConvetionValideePedagogiquement() == "1") {
                                                echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
                                            }
                                            ?>
                                        </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="/conventions/<?= $convention->getNumConvention(); ?>"
                               class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                plus</a>
                        </td>
                    </tr>
                <?php }
            }
            if ($count == 0) { ?>
                <tr>
                    <td colspan="4" class="text-center py-4">Aucune convention trouvée</td>
                </tr>
        <?php }
        } ?>
        </tbody>

    </table>
</div>