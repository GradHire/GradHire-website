<?php
/** @var $offres \app\src\model\dataObject\Offre */

?>

<form method="GET" action="offres" class="w-full gap-4 flex flex-col">
    <div class="flex flex-row gap-2 w-full">
        <a href="/offres/create" class="border-2 border-zinc-200 rounded-lg bg-zinc-50 p-3 px-4 flex justify-center items-center cursor-pointer">
            <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>

        </a>
        <div class="w-full">
            <label for="default-search" class="text-sm font-medium text-zinc-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-zinc-500 dark:text-zinc-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="default-search"
                       class="block w-full p-4 pl-10 text-sm text-zinc-900 border-2 border-zinc-200 rounded-lg bg-zinc-50 focus:ring-zinc-500 focus:border-zinc-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500"
                       placeholder="Search Stages, Alternances...">
                <button type="submit"
                        class="text-white absolute right-2.5 bottom-2.5 bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">
                    Search
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 ">
        <div class="rounded-lg p-4 border-2 border-zinc-200">
            <?php require_once __DIR__ . '/search.php'; ?>
        </div>
        <div class="lg:col-span-3 rounded-lg">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
                <?php
                if ($offres != null) {
                    foreach ($offres as $offre) {
                        require __DIR__ . '/offre.php';
                    }
                } else {
                    require __DIR__ . '/errorOffre.php';
                }
                echo "</div>"; ?>
            </div>
        </div>
</form>
