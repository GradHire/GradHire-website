<?php use app\src\model\Auth;
use app\src\model\Users\Roles;
?>
<div class="flex flex-col w-full relative ">
    <div class="translate-x-2 -translate-y-2  absolute top-0 right-0 flex flex-row gap-2 items-center justify-center">
        <?php     if (Auth::has_role(Roles::Staff, Roles::Manager)) { ?>
        <label for="AcceptConditions" class="relative h-5 w-10 cursor-pointer">
            <input type="checkbox" id="AcceptConditions" class="peer sr-only"/>
            <span class="absolute inset-0 rounded-full  bg-zinc-800 transition border-[1px] border-zinc-100 peer-checked:bg-red-500"></span>
            <span class="absolute shadow inset-y-0 start-0 m-1 h-3 w-3 rounded-full bg-white border-[1px] border-zinc-100 transition-all peer-checked:start-5"></span>
        </label>
        <?php } ?>
        <button class="flex items-center justify-center w-7 h-7 border-[1px] border-zinc-100 duration-150 bg-zinc-50 hover:invert  rounded-full"
            id="reset-button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/>
        </svg>
    </button>
    </div>

    <div class="flex flex-col w-full gap-2">
        <p class="text-md text-black font-bold">Filtres</p>

        <div class="flex flex-row w-full gap-1">
            <div class="w-full">
                <input
                        type="checkbox"
                        name="stage"
                        value="true"
                        id="stage"
                        class="peer hidden"
                />

                <label
                        for="stage"
                        class="flex cursor-pointer items-center justify-center rounded-md border border-zinc-100 bg-white px-3 py-2 text-zinc-700 hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:bg-zinc-500 peer-checked:text-white"
                >
                    <span class="text-sm font-medium">Stage</span>
                </label>
            </div>

            <div class="w-full">
                <input
                        type="checkbox"
                        name="alternance"
                        value="true"
                        id="alternance"
                        class="peer hidden"
                />

                <label
                        for="alternance"
                        class="flex cursor-pointer items-center justify-center rounded-md border border-zinc-100 bg-white px-3 py-2 text-zinc-700 hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:bg-zinc-500 peer-checked:text-white"
                >
                    <span class="text-sm font-medium">Alternance</span>
                </label>
            </div>
        </div>
        <span class="w-full h-[1.5px] my-1 bg-zinc-100 rounded-full"></span>

        <div class="flex flex-col gap-1">
            <p class="text-xs text-zinc-500 font-bold">Annee Visee</p>
            <div>
                <label
                        for="anneeVisee"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                    <select
                            name="anneeVisee"
                            id="anneeVisee"
                            class=" w-full text-zinc-700 rounded-lg sm:text-sm p-2"
                    >
                        <option value="">All</option>
                        <option value="2">BUT2</option>
                        <option value="3">BUT3</option>
                    </select>
                </label>
            </div>
        </div>
        <span class="w-full h-[1.5px] my-1 bg-zinc-100 rounded-full"></span>

        <div class="flex flex-col gap-1">
            <p class="text-xs text-zinc-500 font-bold">Duree</p>
            <div>
                <label
                        for="duree"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                    <select
                            name="duree"
                            id="duree"
                            class=" w-full text-zinc-700 rounded-lg sm:text-sm p-2"
                    >
                        <option value="">All</option>
                        <option value="1">1 Ans</option>
                        <option value="1.5">1 Ans et demie</option>
                        <option value="2">2 Ans</option>
                    </select>
                </label>
            </div>
        </div>
        <span class="w-full h-[1.5px] my-1 bg-zinc-100 rounded-full"></span>

        <div class="flex flex-col gap-1">
            <p class="text-xs text-zinc-500 font-bold">Thematique</p>
            <div>
                <input
                        type="checkbox"
                        name="thematique[]"
                        value="Gestion"
                        id="Gestion"
                        class="peer hidden [&:checked_+_label_svg]:block"
                />

                <label
                        for="Gestion"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                <span class="flex items-center justify-between">
                    <span class="text-zinc-700">Gestion</span>

                    <svg
                            class="hidden h-5 w-5 text-zinc-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </span>
                </label>
            </div>
            <div>
                <input
                        type="checkbox"
                        name="thematique[]"
                        value="Reseaux"
                        id="Reseaux"
                        class="peer hidden [&:checked_+_label_svg]:block"
                />

                <label
                        for="Reseaux"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                <span class="flex items-center justify-between">
                    <span class="text-zinc-700">Reseaux</span>

                    <svg
                            class="hidden h-5 w-5 text-zinc-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </span>
                </label>
            </div>
            <div>
                <input
                        type="checkbox"
                        name="thematique[]"
                        value="Securite"
                        id="Securite"
                        class="peer hidden [&:checked_+_label_svg]:block"
                />

                <label
                        for="Securite"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                <span class="flex items-center justify-between">
                    <span class="text-zinc-700">Securite</span>

                    <svg
                            class="hidden h-5 w-5 text-zinc-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </span>
                </label>
            </div>
            <div>
                <input
                        type="checkbox"
                        name="thematique[]"
                        value="BDD"
                        id="BDD"
                        class="peer hidden [&:checked_+_label_svg]:block"
                />

                <label
                        for="BDD"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                <span class="flex items-center justify-between">
                    <span class="text-zinc-700">Base de Donnée</span>

                    <svg
                            class="hidden h-5 w-5 text-zinc-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </span>
                </label>
            </div>
            <div>
                <input
                        type="checkbox"
                        name="thematique[]"
                        value="DevWeb"
                        id="DevWeb"
                        class="peer hidden [&:checked_+_label_svg]:block"
                />

                <label
                        for="DevWeb"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                <span class="flex items-center justify-between">
                    <span class="text-zinc-700">Développement Web</span>

                    <svg
                            class="hidden h-5 w-5 text-zinc-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </span>
                </label>
            </div>
            <div>
                <input
                        type="checkbox"
                        name="thematique[]"
                        value="DevApp"
                        id="DevApp"
                        class="peer hidden [&:checked_+_label_svg]:block"
                />
                <label
                        for="DevApp"
                        class="block cursor-pointer rounded-lg border border-zinc-100 bg-white p-2 text-sm font-medium hover:border-zinc-200 peer-checked:border-zinc-500 peer-checked:ring-1 peer-checked:ring-zinc-500"
                >
                <span class="flex items-center justify-between">
                    <span class="text-zinc-700">Développement d'application</span>

                    <svg
                            class="hidden h-5 w-5 text-zinc-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                    >
                        <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                        />
                    </svg>
                </span>
                </label>
            </div>
        </div>
        <span class="w-full h-[1.5px] my-1 bg-zinc-100 rounded-full"></span>

        <div class="flex flex-col gap-1">
            <p class="text-xs text-zinc-500 font-bold">Gratification</p>
            <div class="flex w-full flex-row justify-between items-center text-xs text-zinc-500">
                <span id="range1">0</span>
                <span id="range2">100</span>
            </div>
            <div class="relative w-full h-[25px]">
                <div class="slider-track"></div>
                <input type="range" min="4.05" name="gratificationMin" max="15" value="4.05" step="0.01"
                       id="slider-1" oninput="slideOne()">
                <input type="range" min="4.05" name="gratificationMax" max="15" value="15" step="0.01" id="slider-2"
                       oninput="slideTwo()">

            </div>
        </div>
    </div>
</div>

