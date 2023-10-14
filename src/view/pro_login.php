<?php

/** @var $form \app\src\model\Form\FormModel */

?>

<div class="w-full pt-12 pb-24 gap-4 flex flex-col">

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
