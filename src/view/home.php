<?php
$this->title = 'Home';
?>
<div class="bg-white">
	<header class="relative overflow-hidden">
		<div class="pb-80 pt-16 sm:pb-40 sm:pt-24 lg:pb-48 lg:pt-40">
			<div class="relative w-full">
				<div class="sm:max-w-lg">
					<h1 class="text-4xl font-bold tracking-tight text-zinc-900 sm:text-6xl">Trouvez votre stage ou
					                                                                        alternance facilement</h1>
					<p class="mt-4 text-xl text-zinc-500">GradHire est un site de recherche de stage et d'alternance
					                                      pour les étudiants et les entreprises.</p>
				</div>
				<div>
					<div class="mt-10">
						<div aria-hidden="true"
						     class="pointer-events-none lg:absolute lg:inset-y-0 lg:mx-auto lg:w-full lg:max-w-7xl">
							<div class="absolute transform sm:left-1/2 sm:top-0 sm:translate-x-8 lg:left-1/2 lg:top-1/2 lg:-translate-y-1/2 lg:translate-x-8">
								<div class="flex items-center space-x-6 lg:space-x-8">
									<div class="grid flex-shrink-0 grid-cols-1 gap-y-6 lg:gap-y-8">
										<div class="h-64 w-44 overflow-hidden rounded-lg sm:opacity-0 lg:opacity-100">
											<img src="/resources/images/home/img.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
										<div class="h-64 w-44 overflow-hidden rounded-lg">
											<img src="/resources/images/home/img_1.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
									</div>
									<div class="grid flex-shrink-0 grid-cols-1 gap-y-6 lg:gap-y-8">
										<div class="h-64 w-44 overflow-hidden rounded-lg">
											<img src="/resources/images/home/img_2.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
										<div class="h-64 w-44 overflow-hidden rounded-lg">
											<img src="/resources/images/home/img_3.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
										<div class="h-64 w-44 overflow-hidden rounded-lg">
											<img src="/resources/images/home/img_4.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
									</div>
									<div class="grid flex-shrink-0 grid-cols-1 gap-y-6 lg:gap-y-8">
										<div class="h-64 w-44 overflow-hidden rounded-lg">
											<img src="/resources/images/home/img_5.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
										<div class="h-64 w-44 overflow-hidden rounded-lg">
											<img src="/resources/images/home/img_6.png" alt=""
											     class="h-full w-full object-cover object-center">
										</div>
									</div>
								</div>
							</div>
						</div>

						<a href="/offres"
						   class="inline-block rounded-md border border-transparent px-8 py-3 text-center font-medium text-white hover:bg-blue-800 bg-blue-600">Trouver
						                                                                                                                                        une
						                                                                                                                                        offre</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<main>
		<section aria-labelledby="category-heading" class="pt-24 sm:pt-32 w-full">
			<div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8 xl:px-0">
				<h2 id="category-heading" class="text-2xl font-bold tracking-tight text-zinc-900">Offres par
				                                                                                  catégorie</h2>
				<a href="/offres" class="hidden text-sm font-semibold text-zinc-600 hover:text-zinc-500 sm:block">
					Voir toutes les catégories
					<span aria-hidden="true"> &rarr;</span>
				</a>
			</div>

			<div class="mt-4 flow-root">
				<div class="-my-2">
					<div class="relative box-content h-80 overflow-x-auto py-2 xl:overflow-visible">
						<div class="absolute flex space-x-8 px-4 sm:px-6 lg:px-8 xl:relative xl:grid xl:grid-cols-5 xl:gap-x-8 xl:space-x-0 xl:px-0">
							<a href="/offres?thematique[]=DevWeb"
							   class="relative flex h-80 w-56 flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                <span aria-hidden="true" class="absolute inset-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-full w-full object-cover p-8 text-zinc-400 object-center">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
</svg>

                </span>
								<span aria-hidden="true"
								      class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-zinc-800 opacity-50"></span>
								<span class="relative mt-auto text-center text-xl font-bold text-white">Developpement Web</span>
							</a>
							<a href="/offres?thematique[]=DevApp"
							   class="relative flex h-80 w-56 flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                <span aria-hidden="true" class="absolute inset-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-full w-full object-cover p-8 text-zinc-400 object-center">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
</svg>
                </span>
								<span aria-hidden="true"
								      class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-zinc-800 opacity-50"></span>
								<span class="relative mt-auto text-center text-xl font-bold text-white">Developpement Mobile</span>
							</a>
							<a href="/offres?thematique[]=DevApp"
							   class="relative flex h-80 w-56 flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                <span aria-hidden="true" class="absolute inset-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-full w-full object-cover p-8 text-zinc-400 object-center">>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>
</svg>
                </span>
								<span aria-hidden="true"
								      class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-zinc-800 opacity-50"></span>
								<span class="relative mt-auto text-center text-xl font-bold text-white">Developpement Logiciel</span>
							</a>
							<a href="/offres?thematique[]=BDD"
							   class="relative flex h-80 w-56 flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                <span aria-hidden="true" class="absolute inset-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-full w-full object-cover p-8 text-zinc-400 object-center">>
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
</svg>
                </span>
								<span aria-hidden="true"
								      class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-zinc-800 opacity-50"></span>
								<span class="relative mt-auto text-center text-xl font-bold text-white">Data Science et IA</span>
							</a>
							<a href="/offres?thematique[]=Reseaux&thematique[]=Securite"
							   class="relative flex h-80 w-56 flex-col overflow-hidden rounded-lg p-6 hover:opacity-75 xl:w-auto">
                <span aria-hidden="true" class="absolute inset-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="h-full w-full object-cover p-8 text-zinc-400 object-center">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
</svg>
                </span>
								<span aria-hidden="true"
								      class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-zinc-800 opacity-50"></span>
								<span class="relative mt-auto text-center text-xl font-bold text-white">Reseaux et Securité</span>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-6 px-4 sm:hidden">
				<a href="#" class="block text-sm font-semibold text-zinc-600 hover:text-zinc-500">
					Browse all categories
					<span aria-hidden="true"> &rarr;</span>
				</a>
			</div>
		</section>
		<section aria-labelledby="social-impact-heading" class="w-full pt-24 sm:pt-32">
			<div class="relative overflow-hidden rounded-lg">
				<div class="absolute inset-0">
					<img src="/resources/images/home/8.png" alt="" class="h-full w-full object-cover object-center">
				</div>
				<div class="relative bg-zinc-900 bg-opacity-75 px-6 py-32 sm:px-12 sm:py-40 lg:px-16">
					<div class="relative mx-auto flex max-w-3xl flex-col items-center text-center">
						<h2 id="social-impact-heading" class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
							<span class="block sm:inline">Nous sommes là </span>
							<span class="block sm:inline">pour vous aider</span>
						</h2>
						<p class="mt-3 text-xl text-white">Nous vous aidons à trouver le stage ou l'alternance qui vous
						                                   convient le mieux. Vous pouvez également trouver des
						                                   candidats pour votre entreprise.</p>
						<a href="/offres"
						   class="mt-8 block w-full rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-zinc-900 hover:bg-zinc-100 sm:w-auto">Voir
						                                                                                                                                                     plus</a>
					</div>
				</div>
			</div>
		</section>
		<section aria-labelledby="testimonial-heading"
		         class="relative w-full py-24 lg:py-32">
			<div class="mx-auto max-w-2xl lg:max-w-none">
				<h2 id="testimonial-heading" class="text-2xl font-bold tracking-tight text-zinc-900">Ce que les gens
				                                                                                     disent</h2>

				<div class="mt-16 space-y-16 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:space-y-0">
					<blockquote class="sm:flex lg:block">
						<svg width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"
						     class="flex-shrink-0 text-zinc-300">
							<path d="M0 18h8.7v-5.555c-.024-3.906 1.113-6.841 2.892-9.68L6.452 0C3.188 2.644-.026 7.86 0 12.469V18zm12.408 0h8.7v-5.555C21.083 8.539 22.22 5.604 24 2.765L18.859 0c-3.263 2.644-6.476 7.86-6.451 12.469V18z"
							      fill="currentColor"/>
						</svg>
						<div class="mt-8 sm:ml-6 sm:mt-0 lg:ml-0 lg:mt-10">
							<p class="text-lg text-zinc-600">J'ai trouvé un stage en quelques jours. Je suis très
							                                 satisfait de ce site.</p>
							<cite class="mt-4 block font-semibold not-italic text-zinc-900">Giovanni Gozzo</cite>
						</div>
					</blockquote>
					<blockquote class="sm:flex lg:block">
						<svg width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"
						     class="flex-shrink-0 text-zinc-300">
							<path d="M0 18h8.7v-5.555c-.024-3.906 1.113-6.841 2.892-9.68L6.452 0C3.188 2.644-.026 7.86 0 12.469V18zm12.408 0h8.7v-5.555C21.083 8.539 22.22 5.604 24 2.765L18.859 0c-3.263 2.644-6.476 7.86-6.451 12.469V18z"
							      fill="currentColor"/>
						</svg>
						<div class="mt-8 sm:ml-6 sm:mt-0 lg:ml-0 lg:mt-10">
							<p class="text-lg text-zinc-600">Je n'ai pas eu de chance avec les autres sites, mais
							                                 GradHire m'a aidé à trouver un stage en quelques
							                                 semaines.</p>
							<cite class="mt-4 block font-semibold not-italic text-zinc-900">Clement Garro</cite>
						</div>
					</blockquote>
					<blockquote class="sm:flex lg:block">
						<svg width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"
						     class="flex-shrink-0 text-zinc-300">
							<path d="M0 18h8.7v-5.555c-.024-3.906 1.113-6.841 2.892-9.68L6.452 0C3.188 2.644-.026 7.86 0 12.469V18zm12.408 0h8.7v-5.555C21.083 8.539 22.22 5.604 24 2.765L18.859 0c-3.263 2.644-6.476 7.86-6.451 12.469V18z"
							      fill="currentColor"/>
						</svg>
						<div class="mt-8 sm:ml-6 sm:mt-0 lg:ml-0 lg:mt-10">
							<p class="text-lg text-zinc-600">Ce site est incroyable. J'ai pu trouver une entreprise
							                                 magnifique pour mon stage.</p>
							<cite class="mt-4 block font-semibold not-italic text-zinc-900">Lucas Romero</cite>
						</div>
					</blockquote>
				</div>
			</div>
		</section>
	</main>
</div>
