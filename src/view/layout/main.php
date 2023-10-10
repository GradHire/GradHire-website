<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GradHire</title>
	<link rel="stylesheet" href="/resources/css/input.css">
	<link rel="stylesheet" href="/resources/css/output.css">
</head>
<body>

<nav class="bg-white border-zinc-200 dark:bg-zinc-900 shadow">
	<div class="flex flex-wrap items-center justify-between md:w-[75%] w-full max-w-[1200px] mx-auto p-4">
		<a href="/" class="flex items-center">
			<img src="/resources/images/logo.png" class="h-8">
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
							Logout
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
					<a href="/offres"
					   class="block py-2 pl-3 pr-4 text-zinc-900 border-b border-zinc-100 hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-white md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700">Offres</a>
				</li>
				<li>
					<a href="/entreprises"
					   class="block py-2 pl-3 pr-4 text-zinc-900 border-b border-zinc-100 hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-white md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700">Entreprises</a>
				</li>

				<li>
					<a href="/candidatures"
					   class="block py-2 pl-3 pr-4 text-zinc-900 border-b border-zinc-100 hover:bg-zinc-50 md:hover:bg-transparent md:border-0 md:hover:text-zinc-600 md:p-0 dark:text-white md:dark:hover:text-zinc-500 dark:hover:bg-zinc-700 dark:hover:text-zinc-500 md:dark:hover:bg-transparent dark:border-zinc-700">Candidatures</a>
				</li>
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