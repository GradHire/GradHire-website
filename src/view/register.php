<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md pt-12 pb-24 gap-2 flex flex-col">
	<h2 class="text-3xl">Nouveau compte Pro</h2>
	<span class="text-zinc-600 mb-5">Vous avez déjà un compte ? <a href="/register"
	                                                               class="underline">Se connecter</a></span>
	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->print_all_fields();
		$form->getError();
		$form->submit("Créer un compte");
		?>
	</div>
	<?php $form->end(); ?>
	<div class="text-center my-4">
		<hr class="border-t-2 border-zinc-300 w-full mx-auto">
		<span class="bg-white px-2 relative" style="top: -0.75rem;">ou</span>
	</div>
	<a class="text-white w-full bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800"
	   href="/login">Connexion via LDAP</a>
</div>
