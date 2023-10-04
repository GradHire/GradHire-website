<form>
    <div class="flex flex-row w-full">
        <div class="flex flex-col">
            <div class="flex flex-row">
                <label for="search-dropdown"
                       class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"></label>
                <button id="dropdown-button" data-dropdown-toggle="dropdown"
                        class="min-w-[150px] inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
                        type="button">All categories
                    <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
            </div class="flex flex-row w-full">
            <div id="dropdown"
                 class=" hidden  bg-white divide-y divide-gray-100 rounded-lg shadow w-full dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                    <li>
                        <button type="button"
                                class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            Duree
                        </button>
                    </li>
                    <li>
                        <button type="button"
                                class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            Thematique
                        </button>
                    </li>
                    <li>
                        <button type="button"
                                class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            Date de debut
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex flex-row w-full">
            <input type="search" id="search-dropdown"
                   class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-r-none border-l-gray-50 border-l-2 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-l-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-gray-500"
                   placeholder="Search ..." required>
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
