<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>


<div class="w-full max-w-md pt-12 pb-24 gap-2 flex flex-col">
	<h2 class="text-3xl">Connexion Pro</h2>
	<span class="text-gray-600 mb-5">Pas encore de compte ? <a href="/register"
	                                                           class="underline">Créer un compte</a></span>
	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->print_all_fields();
		$form->getError();
		$form->submit("Se connecter");
		?>
	</div>
	<?php $form->end(); ?>
	<div class="text-center my-4">
		<hr class="border-t-2 border-gray-300 w-full mx-auto">
		<span class="bg-white px-2 relative" style="top: -0.75rem;">ou</span>
	</div>
	<a class="text-white w-full bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
	   href="/login">Connexion via LDAP</a>
</div>

