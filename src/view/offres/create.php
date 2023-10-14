<?php

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\repository\OffresRepository;
use app\src\model\Users\Roles;

use app\src\model\repository\EntrepriseRepository;

Auth::check_role(Roles::Enterprise, Roles::Manager); ?>
<div class="w-full flex flex-col max-w-[75%]">
    <?php
    $offred= (new OffresRepository)->draftExist( Application::getUser()->id() );
    $offrechoisi=$offred[0];
    if(Application::getUser()->role()===Roles::Enterprise){

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
                            if($x===0) echo "<option value='" . $offre->getIdoffre() . "'>" . "Page création offre" ."</option>";
                            else echo "<option value='" . $offre->getIdoffre() . "'>" . "Brouillon " . $x . " - " . $offre->getSujet() . "</option>";
                            $x++;
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>
        <?php
        if(isset($_GET["entreprise"])){
            $offrechoisi = (new OffresRepository)->getById($_GET["entreprise"]);
            if($offrechoisi===null){
                $offrechoisi=$offred[0];
            }
        }
    }
    ?>
    <form action="create" method="post" class="w-full flex flex-col" id="myform">
        <div class="w-full gap-4 flex flex-col">

            <?php
            if(Application::getUser()->role()===Roles::Manager){
                ?>
                <div>
                    <label for="entreprise">Entreprise</label>
                    <select name="entreprise" id="entreprise" required
                            class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
                        <option disabled selected value="">Selectionne une entreprise</option>
                        <?php
                        $entrepriseRepo = new EntrepriseRepository();
                        $entreprises = $entrepriseRepo->getAll();
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
                    <input type="radio" class="form-radio" name="radios" value="stage" required
                           onchange="showDistanciel()" checked>
                    <span class="ml-2">Stage</span>
                </label>
                <label class="flex items-center ml-6">
                    <input type="radio" class="form-radio" name="radios" value="alternance" id="alternanceRadio"
                           required onchange="showDistanciel()">
                    <span class="ml-2">Alternance</span>
                </label>
            </div>
            <div>
                <label for="titre">Titre du poste</label>
                <input type="text" placeholder="Développeur web" name="titre" id="titre" required value="<?php echo $offrechoisi->getSujet(); ?>"
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>
            <div>
                <label for="theme">Thématique</label>
                <select name="theme" id="theme" required value="<?php echo $offrechoisi->getThematique(); ?>"
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
                    <label for="nbjour">Nombre jours semaine</label>
                    <input type="number" placeholder="5" min="1" max="7" name="nbjour" id="nbjour" value="<?php echo $offrechoisi->getNbjourtravailhebdo();?>" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="nbheure">Nombre d'heure par jour</label>
                    <input type="number" placeholder="7" min="1" max="12" name="nbheure" id="nbheure" value="<?php echo $offrechoisi->getNbHeureTravailHebdo();?>" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full" id="distancielDiv" style="display: none;">
                    <label for="distanciel">Nombre de jour a distanciel</label>
                    <input type="number" placeholder="0" min="0" name="distanciel" id="distanciel" value="0"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
            </div>

            <div>
                <label for="salaire">Tarif Horraire</label>
                <input type="number" name="salaire" id="salaire" min="4.05" step="0.01" value="<?php echo $offrechoisi->getGratification();?>" required
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>

            <div>
                <label for="avantage">Avantage</label>
                <input type="text" placeholder="Avantage" name="avantage" id="avantage" required value="<?php echo $offrechoisi->getAvantageNature(); ?>"
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>
            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <label for="dated">Date de début</label>
                    <input type="date" placeholder="2021-01-01" name="dated" id="dated" required value="<?php echo $offrechoisi->getDateDebut(); ?>"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="datef">Date de fin</label>
                    <input type="date" placeholder="2021-01-01" name="datef" id="datef" min=required value="<?php echo $offrechoisi->getDateFin(); ?>"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <input type='hidden' name='id_offre' value='<?php echo $offrechoisi->getIdoffre() ?>'>
                <div class="w-full">
                    <label for="theme">Durée</label>
                    <select name="duree"
                            id="duree"
                            required
                            class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light">
                        <option disabled value="">Selectionne une durée</option>
                        <option value="1" <?= $offrechoisi->getDuree() == 1 ? 'selected' : ''; ?>>1 an</option>
                        <option value="1.5" <?= $offrechoisi->getDuree() == 1.5 ? 'selected' : ''; ?>>1 an et 6 mois</option>
                        <option value="2" <?= $offrechoisi->getDuree() == 2 ? 'selected' : ''; ?>>2 ans</option>
                    </select>
                </div>

            </div>

            <label for="description">Description</label>
            <textarea name="description"
                      id="description"
                      cols="30"
                      rows="10"
                      required
                      class="shadow-sm max-h-[100px] bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light ">
<?= $offrechoisi->getDescription() ?>
</textarea>
            <div class="flex flex-row gap-4 w-full">
                <input type="submit" name="action" value="Envoyer"
                       class="w-full text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>

                <?php if(Application::getUser()->role()===Roles::Enterprise){ ?>
                    <input type='hidden' name='sauvegarder_action' value='sauvegarder'>
                    <input onclick="saveForm()" type="submit" name="action" value="sauvegarder"
                           class=" max-w-[150px] text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
                <?php } ?>
            </div>
            <?php if(Application::getUser()->role()===Roles::Enterprise && $offrechoisi->getIdoffre()!=""){ ?>
            <input type='hidden' name='supprimer_action' value='supprimer'>
            <input onclick="saveForm()" type="submit" name="action" value="Supprimer Brouillon"
                   class="w-full text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
            <?php } ?>
        </div>
    </form>
</div>
<script>

    function showDistanciel() {
        var radios = document.getElementById("alternanceRadio");
        var distancielDiv = document.getElementById('distancielDiv');
        distancielDiv.style.display = radios.checked ? 'block' : 'none'
    }

    window.addEventListener('DOMContentLoaded', (event) => { // This is added to ensure the script runs after the DOM is ready.
        var datedElem = document.getElementById('dated');
        var datefElem = document.getElementById('datef');

        var nbjour = document.getElementById('nbjour');
        var distanciel = document.getElementById('distanciel');

        datedElem.addEventListener('change', function() {
            if (datefElem.value && (this.value > datefElem.value)) {
                datefElem.setCustomValidity("Date de début ne peut pas être postérieure à la date de fin!");
            } else {
                datefElem.setCustomValidity("");
            }
        });

        datefElem.addEventListener('change', function() {
            if (datedElem.value && (this.value < datedElem.value)) {
                this.setCustomValidity("Date de fin ne peut pas être antérieure à la date de début!");
            } else {
                this.setCustomValidity("");
            }
        });

        nbjour.addEventListener('change', function() {
            if (distanciel.value && (this.value < distanciel.value)) {
                this.setCustomValidity("Insérer une valeur valide");
            } else {
                this.setCustomValidity("");
            }
        });
        distanciel.addEventListener('change', function() {
            if (nbjour.value && (this.value > nbjour.value)) {
                this.setCustomValidity("Insérer une valeur valide");
            } else {
                this.setCustomValidity("");
            }
        });

    });
    function saveForm() {
        var elements = document.querySelectorAll('[required]');
        for (var i = 0; i < elements.length; i++) {
            elements[i].removeAttribute('required');
            if(elements[i].id==="nbheure" || elements[i].id==="dated" || elements[i].id==="salaire" || elements[i].id==="datef") elements[i].setAttribute('required', 'required');
        }
    }


    function refreshPageWithNewOffer() {
        var selectElement = document.getElementById('entreprise');
        var selectedValue = selectElement.value;
        localStorage.setItem('selectedValue', selectedValue);

        document.getElementById('offreForm').submit();
    }
    window.onload = function() {
        var selectElement = document.getElementById('entreprise');

        var storedValue = localStorage.getItem('selectedValue');

        if(storedValue) {
            selectElement.value = storedValue;
        }
    }

</script>
