<?php
/** @var $exception Exception */

?>

<div class="absolute top-0 left-0 w-full h-full flex flex-col justify-center items-center">
	<div class="text-center">
		<p class="text-base font-semibold text-zinc-600"><?= $exception->getCode() ?></p>
		<h1 class="mt-4 text-3xl font-bold tracking-tight text-zinc-900 sm:text-5xl"><?= $exception->title ?>
		</h1>
		<p class="mt-6 text-base leading-7 text-zinc-600"><?= $exception->getMessage() ?></p>
		<div class="mt-10 flex items-center justify-center gap-x-6">
			<a href="/"
			   class="rounded-md hover:bg-blue-800 bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-600">
				Retour
			</a>
		</div>
	</div>
</div>

