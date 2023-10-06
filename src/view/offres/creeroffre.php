<div class="w-full flex flex-col">
    <form action="creeroffre" method="post">
        <div class="w-full gap-4 flex flex-col">

            <div class="flex mt-4">
                <label class="flex items-center">
                    <input type="radio" class="form-radio" name="radios" value="stage" required
                           onchange="showDistanciel()">
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
                <input type="text" placeholder="Développeur web" name="titre" id="titre" required
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>
            <div>
                <label for="theme">Thématique</label>
                <select name="theme" id="theme" required
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
                    <input type="number" placeholder="5" min="1" max="7" name="nbjour" id="nbjour" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="nbheure">Nombre d'heure par jour</label>
                    <input type="number" placeholder="7" min="1" max="12" name="nbheure" id="nbheure" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full" id="distancielDiv" style="display: none;">
                    <label for="distanciel">Nombre de jour a distanciel</label>
                    <input type="number" placeholder="0" min="0" max="7" name="distanciel" id="distanciel"
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
            </div>

            <div>

                <label for="salaire">Tarif Horraire</label>
                <input type="number" placeholder="4.05" name="salaire" id="salaire" min="4.05" required
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>

            <div>
                <label for="avantage">Avantage</label>
                <input type="text" placeholder="Avantage" name="avantage" id="avantage" required
                       class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
            </div>
            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <label for="dated">Date de début</label>
                    <input type="date" placeholder="2021-01-01" name="dated" id="dated" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="datef">Date de fin</label>
                    <input type="date" placeholder="2021-01-01" name="datef" id="datef" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
                <div class="w-full">
                    <label for="duree">Duree</label>
                    <input type="number" placeholder="1.5" name="duree" id="duree" required
                           class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "/>
                </div>
            </div>

            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="10" required
                      class="shadow-sm max-h-[100px] bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light "></textarea>

            <input type='hidden' name='action' value='createoffre'>
            <input type="submit" value="Envoyer"
                   class="text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"/>
        </div>
    </form>
</div>
<script>
    function showDistanciel() {
        var radios = document.getElementById("alternanceRadio");
        var distancielDiv = document.getElementById('distancielDiv');
        distancielDiv.style.display = radios.checked ? 'block' : 'none'
    }
</script>