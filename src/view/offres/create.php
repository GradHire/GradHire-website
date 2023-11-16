<?php

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\repository\OffresRepository;
use app\src\model\dataObject\Roles;

use app\src\model\repository\EntrepriseRepository;

Auth::check_role(Roles::Enterprise, Roles::Manager); ?>
<div class="w-full max-w-md flex flex-col pt-12 pb-24">
    <?php
    $offred = (new OffresRepository)->draftExist(Application::getUser()->id());
    //si offred est vide
    if ($offred != null) {
        $offrechoisi = $offred[0];
        if (Application::getUser()->role() === Roles::Enterprise) {
            ?>
            <form method="get" id="offreForm" class="w-full flex flex-col">
                <div class="w-full gap-4 flex flex-col">
                    <div class="flex mt-4">
                        <label for="entreprise">Choisis ton brouillon</label>
                        <select name="entreprise" id="entreprise" onchange="refreshPageWithNewOffer()"
                                class=" bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm
                           rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5
                           dark:bg-zinc-700 dark:border-zinc-600
                           dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
                            <option disabled selected value="">Selectionne un brouillon</option>
                            <?php
                            $x = 0;
                            foreach ($offred as $offre) {
                                if ($x === 0) echo "<option value='" . $offre->getIdoffre() . "'>" . "Page création offre" . "</option>";
                                else echo "<option value='" . $offre->getIdoffre() . "'>" . "Brouillon " . $x . " - " . $offre->getSujet() . "</option>";
                                $x++;
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_GET["entreprise"])) {
                $offrechoisi = (new OffresRepository)->getById($_GET["entreprise"]);
                if ($offrechoisi === null) {
                    $offrechoisi = $offred[0];
                }
            }
        }
    }
    ?>
    <form action="create" method="post" class="w-full flex flex-col" id="myform">
        <div class="w-full gap-4 flex flex-col">

            <?php
            if (Application::getUser()->role() === Roles::Manager) {
                ?>
                <div>
                    <label for="identreprise">Entreprise</label>
                    <select name="identreprise" id="entreprise" required
                            class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
                        <option disabled selected value="">Selectionne une entreprise</option>
                        <?php
                        $entreprises = (new EntrepriseRepository([]))->getAll();
                        foreach ($entreprises as $entreprise) {
                            echo "<option value='" . $entreprise->getIdutilisateur() . "'>" . $entreprise->getNomutilisateur() . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <?php
            }
            ?>

            <div class="flex mt-4">
                <label class="flex items-center">
                    <input type="checkbox" class="form-radio" name="typeStage" value="stage" id="stageCheckbox"
                           onchange="showFormType()">
                    <span class="ml-2">Stage</span>
                </label>
                <label class="flex items-center ml-6">
                    <input type="checkbox" class="form-radio" name="typeAlternance" value="alternance"
                           id="alternanceCheckbox"
                           onchange="showFormType()">
                    <span class="ml-2">Alternance</span>
                </label>
            </div>
            <div>
                <label for="sujet">Titre du poste</label>
                <input type="text" placeholder="Développeur ninja SQL" name="sujet" id="titre" required
                       value="<?php if ($offred != null) echo $offrechoisi->getSujet(); ?>" maxlength="50"
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>
            <div>
                <label for="theme">Thématique</label>
                <select name="theme" id="theme" required
                        value="<?php if ($offred != null) echo $offrechoisi->getThematique(); ?>"
                        class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
                    <option disabled selected value="">Selectionne une thématique</option>
                    <option value="gestion">Gestion</option>
                    <option value="bdd">Base de donnée</option>
                    <option value="reseaux">Réseaux</option>
                    <option value="secu">Sécurité</option>
                    <option value="développement web">Développement Web</option>
                    <option value="dev app">Développement Application</option>
                </select>
            </div>
            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <label for="nbjourtravailhebdo">Nombre de jours de Travail</label>
                    <input type="number" placeholder="5" min="1" max="7" name="nbjourtravailhebdo" id="nbjour"
                           value="<?php if ($offred != null) echo $offrechoisi->getNbjourtravailhebdo(); ?>" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="nbheureparjour">Nombre d'heure par jour</label>
                    <input type="number" placeholder="7" min="1" max="12" name="nbheureparjour" id="nbheure"
                           value="<?php if ($offred != null) echo $offrechoisi->getNbHeureTravailHebdo(); ?>" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full" id="distancielDiv" style="display: none;">
                    <label for="distanciel">Nombre de jour a distanciel</label>
                    <input type="number" placeholder="0" min="0" name="distanciel" id="distanciel" value="0"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
            </div>

            <div>
                <label for="gratification">Tarif Horraire</label>
                <input type="number" name="gratification" id="salaire" min="4.05" step="0.01"
                       value="<?php if ($offred != null) echo $offrechoisi->getGratification(); ?>" required
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>

            <div>
                <label for="avantage">Avantage</label>
                <input type="text" placeholder="Avantage" name="avantage" id="avantage" required
                       value="<?php if ($offred != null) echo $offrechoisi->getAvantageNature(); ?>"
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>
            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <label for="datedebut">Date de début</label>
                    <input type="date" placeholder="2021-01-01" name="datedebut" id="dated" required
                           value="<?php if ($offred != null) echo $offrechoisi->getDateDebut(); ?>"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="datefin">Date de fin</label>
                    <input type="date" placeholder="2021-01-01" name="datefin" id="datef" min=required
                           value="<?php if ($offred != null) echo $offrechoisi->getDateFin(); ?>"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <input type='hidden' name='id_offre'
                       value='<?php if ($offred != null) echo $offrechoisi->getIdoffre() ?>'>
            </div>
            <div class="flex flex-row gap-4">
                <div class="w-full" id="dureeSelectDiv" style="display: none;">
                    <label for="dureeAlternance">Durée Alternance</label>
                    <select name="dureeAlternance"
                            id="dureeAlternance"
                            required
                            class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
                        <option disabled value="">Selectionne une durée</option>
                        <option hidden value=""></option>
                        <option value="1" <?php if ($offred != null) echo $offrechoisi->getDuree() == 1 ? 'selected' : ''; ?>>
                            1 an
                        </option>
                        <option value="1.5" <?php if ($offred != null) echo $offrechoisi->getDuree() == 1.5 ? 'selected' : ''; ?>>
                            1 an et 6 mois
                        </option>
                        <option value="2" <?php if ($offred != null) echo $offrechoisi->getDuree() == 2 ? 'selected' : ''; ?>>
                            2 ans
                        </option>
                    </select>
                </div>
                <div class="w-full" id="dureeNumberDiv" style="display: none;">
                    <label for="dureeStage">Durée Stage</label>
                    <input type="number" id="dureeStage" name="dureeStage"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
            </div>
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="10" required
                      class="shadow-sm max-h-[100px] bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light ">
<?php if ($offred != null) echo $offrechoisi->getDescription() ?>
</textarea>
            <div class="flex flex-row gap-4 w-full">
                <input type="submit" name="action" value="Envoyer"
                       class="w-full text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>

                <?php if (Application::getUser()->role() === Roles::Enterprise) { ?>
                    <input type='hidden' name='sauvegarder_action' value='sauvegarder'>
                    <input onclick="saveForm()" type="submit" name="action" value="sauvegarder"
                           class=" max-w-[150px] text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
                <?php } ?>
            </div>
            <?php if ($offred != null) {
                if (Application::getUser()->role() === Roles::Enterprise && $offrechoisi->getIdoffre() != "") { ?>
                    <input type='hidden' name='supprimer_action' value='supprimer'>
                    <input onclick="saveForm()" type="submit" name="action" value="Supprimer Brouillon"
                           class="w-full text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
                <?php }
            } ?>
        </div>
    </form>
</div>
<script src="ressources/js/createOffre.js"></script>