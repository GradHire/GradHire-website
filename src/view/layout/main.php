<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->title ?></title>
    <link rel="stylesheet" href="/resources/css/input.css">
    <link rel="stylesheet" href="/resources/css/output.css">
</head>
<body>

<nav class="bg-white border-zinc-200 dark:bg-zinc-900 shadow">
    <div class="flex flex-wrap items-center justify-between md:w-[75%] w-full max-w-[1200px] mx-auto p-4">
        <a href="/" class="flex items-center">
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">GradHire</span>
        </a>
        <div class="flex items-center md:order-2">
            <?php use app\src\model\Application;

            if (Application::isGuest()): ?>
                <a href="/login"
                   class="text-zinc-800 dark:text-white hover:bg-zinc-50 focus:ring-4 focus:ring-zinc-300 font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 mr-1 md:mr-2 dark:hover:bg-zinc-700 focus:outline-none dark:focus:ring-zinc-800">Login</a>
                <a href="/register"
                   class="text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:ring-zinc-300 font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 mr-1 md:mr-2 dark:bg-zinc-600 dark:hover:bg-zinc-700 focus:outline-none dark:focus:ring-zinc-800">Sign
                    up</a>
            <?php else: ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/profile">
                            Profile
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/logout">
                            Welcome <?= $_SESSION["full_name"] ?> (Logout)
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
            <button data-collapse-toggle="mega-menu-icons" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-zinc-500 rounded-lg md:hidden hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-zinc-200 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:focus:ring-zinc-600"
                    aria-controls="mega-menu-icons" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
        </div>
        <div id="mega-menu-icons" class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
            <ul class="flex flex-col mt-4 font-medium md:flex-row md:space-x-8 md:mt-0">
                <li>
                    <a href="/"
                       class="block py-2 pl-3 pr-4 text-zinc-600 border-b border-zinc-100 hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-zinc-500 md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700"
                       aria-current="page">Home</a>
                </li>
                <li>
                    <a href="/dashboard"
                       class="block py-2 pl-3 pr-4 text-zinc-600 border-b border-zinc-100 hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-zinc-500 md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700"
                       aria-current="page">Dashboard</a>
                </li>
                <li>
                    <a href="/offres"
                       class="block py-2 pl-3 pr-4 text-zinc-900 border-b border-zinc-100 hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-white md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700">Offres</a>
                </li>
                <!--                <li id="parent">-->
                <!--                    <div id="company" data-dropdown-toggle="mega-menu-icons-dropdown" class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium text-zinc-900 border-b border-zinc-100 md:w-auto hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-white md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700">-->
                <!--                        Company-->
                <!--                        <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">-->
                <!--                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>-->
                <!--                        </svg>-->
                <!--                    </div>-->
                <!--                    <div id="company-dropdown" class="hidden absolute z-10 w-auto grid-cols-1 text-sm bg-white border border-zinc-100 rounded-lg shadow-md dark:border-zinc-700  dark:bg-zinc-700">-->
                <!--                        <div class="p-4 pb-0 text-zinc-900 md:pb-4 dark:text-white">-->
                <!--                            <ul class="space-y-4" aria-labelledby="mega-menu-icons-dropdown-button">-->
                <!--                                <li>-->
                <!--                                    <a href="#" class="flex items-center text-zinc-500 dark:text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-500 group">-->
                <!--                                        <span class="sr-only">About us</span>-->
                <!--                                        <svg class="w-3 h-3 mr-2 text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-600 dark:group-hover:text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">-->
                <!--                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>-->
                <!--                                        </svg>-->
                <!--                                        About Us-->
                <!--                                    </a>-->
                <!--                                </li>-->
                <!--                                <li>-->
                <!--                                    <a href="#" class="flex items-center text-zinc-500 dark:text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-500 group">-->
                <!--                                        <span class="sr-only">Library</span>-->
                <!--                                        <svg class="w-3 h-3 mr-2 text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-600 dark:group-hover:text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">-->
                <!--                                            <path d="m1.56 6.245 8 3.924a1 1 0 0 0 .88 0l8-3.924a1 1 0 0 0 0-1.8l-8-3.925a1 1 0 0 0-.88 0l-8 3.925a1 1 0 0 0 0 1.8Z"/>-->
                <!--                                            <path d="M18 8.376a1 1 0 0 0-1 1v.163l-7 3.434-7-3.434v-.163a1 1 0 0 0-2 0v.786a1 1 0 0 0 .56.9l8 3.925a1 1 0 0 0 .88 0l8-3.925a1 1 0 0 0 .56-.9v-.786a1 1 0 0 0-1-1Z"/>-->
                <!--                                            <path d="M17.993 13.191a1 1 0 0 0-1 1v.163l-7 3.435-7-3.435v-.163a1 1 0 1 0-2 0v.787a1 1 0 0 0 .56.9l8 3.925a1 1 0 0 0 .88 0l8-3.925a1 1 0 0 0 .56-.9v-.787a1 1 0 0 0-1-1Z"/>-->
                <!--                                        </svg>-->
                <!--                                        Library-->
                <!--                                    </a>-->
                <!--                                </li>-->
                <!--                                <li>-->
                <!--                                    <a href="#" class="flex items-center text-zinc-500 dark:text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-500 group">-->
                <!--                                        <span class="sr-only">Resources</span>-->
                <!--                                        <svg class="w-3 h-3 mr-2 text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-600 dark:group-hover:text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">-->
                <!--                                            <path d="M15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783ZM6 2h6a1 1 0 1 1 0 2H6a1 1 0 0 1 0-2Zm7 5H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Z"/>-->
                <!--                                            <path d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z"/>-->
                <!--                                        </svg>-->
                <!--                                        Resources-->
                <!--                                    </a>-->
                <!--                                </li>-->
                <!--                                <li>-->
                <!--                                    <a href="#" class="flex items-center text-zinc-500 dark:text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-500 group">-->
                <!--                                        <span class="sr-only">Pro Version</span>-->
                <!--                                        <svg class="w-3 h-3 mr-2 text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-600 dark:group-hover:text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">-->
                <!--                                            <path d="m7.164 3.805-4.475.38L.327 6.546a1.114 1.114 0 0 0 .63 1.89l3.2.375 3.007-5.006ZM11.092 15.9l.472 3.14a1.114 1.114 0 0 0 1.89.63l2.36-2.362.38-4.475-5.102 3.067Zm8.617-14.283A1.613 1.613 0 0 0 18.383.291c-1.913-.33-5.811-.736-7.556 1.01-1.98 1.98-6.172 9.491-7.477 11.869a1.1 1.1 0 0 0 .193 1.316l.986.985.985.986a1.1 1.1 0 0 0 1.316.193c2.378-1.3 9.889-5.5 11.869-7.477 1.746-1.745 1.34-5.643 1.01-7.556Zm-3.873 6.268a2.63 2.63 0 1 1-3.72-3.72 2.63 2.63 0 0 1 3.72 3.72Z"/>-->
                <!--                                        </svg>-->
                <!--                                        Pro Version-->
                <!--                                    </a>-->
                <!--                                </li>-->
                <!--                            </ul>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </li>-->

            </ul>
        </div>
    </div>
</nav>
<div class="w-full flex justify-center items-center">
    <div class="md:w-[75%] w-full max-w-[1200px] p-4 flex justify-center items-center">
        {{content}}
    </div>
</div>
</body>
</html>