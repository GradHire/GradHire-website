<?php

/** @var $form \app\src\model\Form\FormModel */

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

	<h1>Pro Login</h1>

	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->print_all_fields();
		$form->submit("Se connecter");
		$form->getError();
		?>
		<a href="register">Créer un compte entreprise</a>
	</div>
	<?php $form->end(); ?>
</div>
