<?php
/** @var $user User */

use app\src\model\Users\User;

?>

<div class="h-full">
	<!-- Card -->
	<div class="max-w-sm mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
		<div class="flex flex-col h-full">
			<!-- Card top -->
			<div class="flex-grow p-5">
				<div class="flex justify-between items-start">
					<!-- Image + name -->
					<header>
						<div class="flex mb-2">
							<div class="rounded-full overflow-hidden h-20 w-20 mr-5">
								<img src="<?= $user->get_picture() ?>" alt="Photo de profil"
								     class="w-full h-full object-cover rounded-full"/>
							</div>
							<div class="mt-1 pr-1">
								<a class="inline-flex text-gray-800 hover:text-gray-900" href="#0">
									<h2 class="text-xl leading-snug justify-center font-semibold"><?= $user->full_name() ?></h2>
								</a>
								<div class="flex items-center">
									<span class="whitespace-nowrap rounded-full bg-zinc-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-zinc-600">Entreprise</span>
								</div>
							</div>
						</div>
					</header>
				</div>
				<!-- Bio -->
				<div class="mt-2">
					<p><?= $user->attributes()["bio"] ?></p>
					<?php if ($user->attributes()["siteweb"]): ?>
						<a href="<?= $user->attributes()["siteweb"] ?>" class="underline accent-blue-500"
						   target="_blank">Site</a>
					<?php endif; ?>
					<?php if ($user->attributes()["effectif"]): ?>
						<p>Effectif <?= $user->attributes()["effectif"] ?></p>
					<?php endif; ?>
				</div>
			</div>
			<!-- Card footer -->
			<div class="border-t border-gray-200">
				<div class="flex divide-x divide-gray-200r">

					<?php if ($user->is_me()) { ?>
						<a class="block flex-1 text-center text-sm text-gray-600 hover:text-gray-800 font-medium px-3 py-4 group"
						   href="edit_profile">
							<div class="flex items-center justify-center">
								<svg class="w-4 h-4 fill-current text-gray-400 group-hover:text-gray-500 flex-shrink-0 mr-2"
								     viewBox="0 0 16 16">
									<path d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"/>
								</svg>
								<span>Edit Profile</span>
							</div>
						</a>
						<a class="block flex-1 text-center text-sm text-gray-600 hover:text-gray-800 font-medium px-3 py-4 group"
						   href="dashboard">
							<div class="flex items-center justify-center">
								<span>Dashboard</span>
							</div>
						</a>
					<?php } else { ?>
						<a class="block flex-1 text-center text-sm text-indigo-500 hover:text-indigo-600 font-medium px-3 py-4"
						   href="mailto:<?= $user->attributes()["emailutilisateur"] ?>" target="_blank">
							<div class="flex items-center justify-center">
								<svg class="w-4 h-4 fill-current flex-shrink-0 mr-2" viewBox="0 0 16 16">
									<path d="M8 0C3.6 0 0 3.1 0 7s3.6 7 8 7h.6l5.4 2v-4.4c1.2-1.2 2-2.8 2-4.6 0-3.9-3.6-7-8-7zm4 10.8v2.3L8.9 12H8c-3.3 0-6-2.2-6-5s2.7-5 6-5 6 2.2 6 5c0 2.2-2 3.8-2 3.8z"/>
								</svg>
								<span>Send Email</span>
							</div>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>