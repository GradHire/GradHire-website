<?php

/** @var $entreprise \app\src\model\dataObject\Entreprise
 * @var $offres \app\src\model\dataObject\Offre
 * @var $avisPublic \app\src\model\dataObject\Avis
 * @var $avisPriver \app\src\model\dataObject\Avis
 */

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\AvisRepository;
use app\src\view\components\ui\Table;

$nom = $entreprise->getNom();
if (empty($nom) || $nom == "") $nom = "Sans nom";

?>
<div class="w-full gap-4 mx-auto">
    <div class="w-full flex md:flex-row flex-col  justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900"><?= $nom ?></h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-zinc-500">
                Structure: <?= $entreprise->getTypestructure() ?? "" ?></p>
        </div>
    </div>
    <div class="mt-6 border-t border-zinc-100">
        <dl class="divide-y divide-zinc-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Effectif</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $effectif = $entreprise->getEffectif();
                    if ($effectif != null) echo $effectif;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Code NAF</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $codeNaf = $entreprise->getCodenaf();
                    if ($codeNaf != null) echo $codeNaf;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Fax</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $fax = $entreprise->getFax();
                    if ($fax != null) echo $fax;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Site web</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $siteWeb = $entreprise->getSiteweb();
                    if ($siteWeb != null) echo "<a target=\"_blank\" href=\"" . $siteWeb . "\">" . $siteWeb . "</a>";
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Numéro de téléphone</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $numTel = $entreprise->getNumtelephone();
                    if ($numTel != null) echo $numTel;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Email</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $email = $entreprise->getEmail();
                    if ($email != null) echo "<a href=\"mailto:" . $email . "\">" . $email . "</a>";
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">SIRET</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $siret = $entreprise->getSiret();
                    if ($siret != null) echo $siret;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-zinc-900">Statut juridique</dt>
                <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                    <?php
                    $statutJuridique = $entreprise->getStatutjuridique();
                    if ($statutJuridique != null) echo $statutJuridique;
                    else echo("Non renseigné");
                    ?></dd>
            </div>
            <?php
            if (Auth::has_role(Roles::ManagerAlternance, Roles::Manager, Roles::TutorTeacher, Roles::Student, Roles::ManagerStage, Roles::Teacher, Roles::Staff)) {
                $avisPublic = AvisRepository::getAvisEntreprisePublic($entreprise->getIdutilisateur());
                $avisPriver = AvisRepository::getAvisEntreprisePriver($entreprise->getIdutilisateur());
                if ($avisPublic != null) {
                    ?>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-zinc-900">Avis public sur l'entreprise :</dt>
                        <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                            <?php
                            foreach ($avisPublic as $avi) {
                                echo "- " . $avi['avis'] . "<br>";
                            }
                            ?>
                    </div>
                <?php }
                if ($avisPriver != null && Auth::has_role(Roles::ManagerAlternance, Roles::Manager, Roles::TutorTeacher, Roles::ManagerStage, Roles::Teacher, Roles::Staff)) {
                    ?>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-zinc-900">Avis Professeur sur l'entreprise :</dt>
                        <dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
                            <?php
                            foreach ($avisPriver as $avi) {
                                echo "- " . $avi['avis'] . "<br>";
                            }
                            ?>
                    </div>
                    <?php
                }
            }
            /**
             * @param $offre
             * @return void
             */
            function print_cell($offre): void
            {
                Table::cell($offre->getSujet());
                Table::cell($offre->getThematique());
                Table::cell($offre->getDateCreation());
                if ($offre->getStatut() == "en attente") Table::chip("En attente", "yellow");
                else if ($offre->getStatut() == "valider") Table::chip("Validée", "green");
                else if ($offre->getStatut() == "archiver") Table::chip("Archivée", "red");
                else if ($offre->getStatut() == "brouillon") Table::chip("Brouillon", "gray");
                Table::cell("<a href=\"/offres/" . $offre->getIdoffre() . "\" class=\"inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700\">Voir plus</a>");
            }

            if ($offres != null)
            Table::createTable($offres, ['sujet', 'thematique', 'dateCreation', 'statut'], function ($offre) {
                print_cell($offre);
            });
            ?>
        </dl>
    </div>
</div>
