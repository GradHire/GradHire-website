<?php

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

\app\src\core\components\Notification::show();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> GradHire | <?= $this->title ?></title>
	<link rel="stylesheet" href="/resources/css/input.css">
	<link rel="stylesheet" href="/resources/css/output.css">
	<link rel="icon" type="image/png" sizes="32x32" href="/resources/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon-16x16.png">
	<style>
        @import url('https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700;800&display=swap');

        nav {
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(20px) saturate(160%) contrast(45%) brightness(140%);
            -webkit-backdrop-filter: blur(20px) saturate(160%) contrast(45%) brightness(140%);
        }
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
	      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
	      crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="font-sans">

<nav aria-label="Top"
     class="fixed z-20 w-full border-b border-gray-200">
	<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 top-0 z-50">
		<div class="flex h-16 gap-4 items-center">
			<?php if (!Application::isGuest()): ?>
				<button id="burger-btn" type="button" class="relative rounded-md bg-white p-2 text-zinc-400 lg:hidden">
					<span class="absolute -inset-0.5"></span>
					<span class="sr-only">Open menu</span>
					<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
					     aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round"
						      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
					</svg>
				</button>
			<?php endif; ?>
			<div class="flex lg:ml-0">
				<a href="/">
					<span class="sr-only">GradHire</span>
					<img class="h-8 w-auto" src="/resources/images/logo.png" alt="">
				</a>
			</div>

			<div class="hidden lg:ml-8 lg:block lg:self-stretch">
				<div class="flex h-full space-x-8">
					<?php if (!Application::isGuest()): ?>
						<a href="/"
						   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Accueil</a>
						<?php if (!Auth::has_role(Roles::ChefDepartment)): ?>
							<a href="/offres"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Offres</a>
						<?php else: ?>
							<a href="/utilisateurs"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Gestion
							                                                                                              roles</a>
						<?php endif; ?>
						<?php if (!Auth::has_role(Roles::Enterprise, Roles::Tutor, Roles::ChefDepartment)): ?>
							<a href="/entreprises"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Entreprises</a>
						<?php endif; ?>
						<?php if (Auth::has_role(Roles::Student, Roles::Teacher, Roles::Tutor, Roles::Enterprise)): ?>
							<a href="/candidatures"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
						<?php endif; ?>
						<?php if (Auth::has_role(Roles::Enterprise)): ?>
							<a href="/offres/create"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Créer
							                                                                                              une
							                                                                                              offre</a>
							<a href="/ListeTuteurPro"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Tuteurs</a>
						<?php endif; ?>
						<?php if (Auth::has_role(Roles::Manager, Roles::Staff)): ?>
							<a href="/utilisateurs"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Utilisateurs</a>
							<a href="/candidatures"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
							<a href="/ListeTuteurPro"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Tuteurs</a>
							<a href="/importer"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Import</a>
						<?php endif; ?>
						<?php if (Auth::has_role(Roles::Student)): ?>
							<a href="/explicationSimu"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Simulateur</a>
						<?php endif; ?>
						<?php if (Auth::has_role(Roles::Enterprise, Roles::Student, Roles::Manager, Roles::Staff)): ?>
							<a href="/conventions"
							   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Conventions</a>
						<?php endif; endif; ?>
				</div>
			</div>

			<div class="ml-auto flex items-center">
				<?php if (Application::isGuest()): ?>
					<div class="flex flex-1 lg:items-center justify-end space-x-6">
						<a href="/login" class="text-sm font-medium text-zinc-700 hover:text-zinc-800">Se connecter</a>
						<span class="h-6 w-px bg-zinc-400" aria-hidden="true"></span>
						<a href="/register" class="text-sm font-medium text-zinc-700 hover:text-zinc-800">S'inscrire</a>
					</div>
				<?php else: ?>
					<div class="flex flex-1 lg:items-center justify-end space-x-6">
						<a class="flex flex-row gap-4 items-center justify-center text-sm font-medium text-zinc-700 hover:text-zinc-800"
						   href="/profile">
                            <span class="max-lg:hidden">
                                <?= Application::getUser()->full_name() ?>
                            </span>
							<div class="rounded-full overflow-hidden h-7 w-7">
								<img src="<?= Application::getUser()->get_picture() ?>" alt="Photo de profil"
								     class="w-full h-full object-cover rounded-full aspect-square">
							</div>
						</a>

						<span class="h-6 w-px bg-zinc-200 max-lg:hidden"
						      aria-hidden="true"></span>
						<a href="/logout"
						   class="text-sm font-medium text-zinc-700 hover:text-zinc-800 <?php if (!Application::isGuest()): ?>max-lg:hidden<?php endif; ?>">Se
						                                                                                                                                    déconnecter</a>

					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</nav>
<div id="nav-container"
     class="hidden fixed top-0 left-0 w-full h-screen bg-white bg-opacity-90 backdrop-blur-xl backdrop-filter z-50  mt-[65px]">
	<div class="flex flex-col justify-center items-center space-y-8 uppercase mt-[50px]">
		<?php if (!Application::isGuest()): ?>
			<a href="/offres"
			   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Offres</a>
			<?php if (Auth::has_role(Roles::Student)): ?>
				<a href="/explicationSimu"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Simulateur</a>
			<?php endif; ?>
			<?php if (!Auth::has_role(Roles::Enterprise)): ?>
				<a href="/entreprises"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Entreprises</a>
			<?php endif; ?>
			<?php if (Auth::has_role(Roles::Teacher)): ?>
				<a href="/candidatures"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
			<?php endif; ?>
			<?php if (Auth::has_role(Roles::Enterprise)): ?>
				<a href="/offres/create"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Créer une
				                                                                                              offre</a>
				<a href="/candidatures"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
				<a href="/ListeTuteurPro"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Tuteurs</a>
			<?php endif; ?>
			<?php if (Auth::has_role(Roles::Manager, Roles::Staff)): ?>
				<a href="/utilisateurs"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Utilisateurs</a>
				<a href="/candidatures"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Candidatures</a>
				<a href="/ListeTuteurPro"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Tuteurs</a>
				<a href="/importer"
				   class="flex items-center text-xl md:text-sm font-medium text-zinc-700 hover:text-zinc-800">Import</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
<div id="blur-background" class="hidden w-screen h-screen fixed z-50 top-0 left-0 backdrop-blur-md"></div>
<div class="w-full flex flex-col justify-center items-center">
	<div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8 flex flex-col justify-center mt-[65px] items-center">
		{{content}}
		<footer aria-labelledby="footer-heading" class="bg-white w-full">
			<h2 id="footer-heading" class="sr-only">Footer</h2>
			<div class="mx-auto max-w-7xl ">
				<div class="border-t border-zinc-200 py-10">
					<p class="text-sm text-zinc-500">Copyright &copy; 2023 -
						<span class="text-zinc-900">GradHire</span>
					</p>
				</div>
			</div>
		</footer>
	</div>
</div>
<script>
    var burgerBtn = document.getElementById("burger-btn");
    var navContainer = document.getElementById("nav-container");

    burgerBtn.addEventListener("click", function () {
        if (navContainer.classList.contains('animate-slide-out') || navContainer.classList.contains('hidden')) {
            navContainer.classList.remove('animate-slide-out', 'hidden');
            navContainer.classList.add('animate-slide-in');
        } else {
            navContainer.classList.remove('animate-slide-in');
            navContainer.classList.add('animate-slide-out');

            // To hide the menu after the animation completes
            setTimeout(function () {
                navContainer.classList.add('hidden');
            }, 500);
        }
    });

</script>
</body>
</html>