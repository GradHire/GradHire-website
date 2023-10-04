<form method="GET" action="offres">
    <div class="flex flex-row w-full">
        <div class="flex flex-col">
            <div class="flex flex-row gap-2">
                <label>
                    <input type="radio" name="anneeVisee" value="2">BUT2
                </label>
                <label>
                    <input type="radio" name="anneeVisee" value="3">BUT3
                </label>
<!--                drop down list for gestion, bdd, réseaux , secu, développement web, dev app where i can choose multiple thematique with just a click-->
                <div id="list1" class="dropdown-check-list" tabindex="100">
                    <span class="anchor">Select Fruits</span>
                    <ul class="items">
                        <li><input type="checkbox" name="thematique" value="Caillou" />Caillou</li>
                        <li><input type="checkbox" name="thematique" value="Eau" />Eau</li>
                        <li><input type="checkbox" name="thematique" value="Gestion" />Gestion</li>
                        <li><input type="checkbox" name="thematique" value="Reseaux" />Réseaux</li>
                        <li><input type="checkbox" name="thematique" value="Securite" />Sécurité</li>
                        <li><input type="checkbox" name="thematique" value="DevWeb" />Développement Web</li>
                        <li><input type="checkbox" name="thematique" value="DevApp" />Développement d'application</li>

                    </ul>
                </div>
                <script>
                    var checkList = document.getElementById('list1');
                    checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
                        if (checkList.classList.contains('visible'))
                            checkList.classList.remove('visible');
                        else
                            checkList.classList.add('visible');
                    }
                </script>
            </div>
            <div class="flex flex-row w-full">
                <input type="search" id="search-dropdown"
                       class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-r-none border-l-gray-50 border-l-2 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-l-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-gray-500"
                       placeholder="Search ..." name="search" >
                <button type="submit"
                        class=" p-2.5 text-sm font-medium text-white bg-gray-700 rounded-r-lg border border-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>
        </div>
    </div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#dropdown-button").click(function () {
            $("#dropdown").toggleClass("hidden");
        });

        $("#dropdown ul li").click(function () {
            let text = $(this).text();
            console.log(text);

            // Do whatever you need with the clicked item's text here.
            // For instance, changing the button text
            $("#dropdown-button").text(text);

            // And then hide dropdown
            $("#dropdown").addClass("hidden");
        });
    });
</script>
