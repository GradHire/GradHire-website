<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto">
    <h2 class="text-3xl">Postuler</h2>
    <span class="text-zinc-600 mb-5">Veuillez remplir les champs pour postuler à l'offre. Pensez également à contacter l'entreprise par mail ou téléphone.</span>
	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->print_all_fields();
		$form->submit("Postuler");
		$form->getError();
		?>
	</div>
	<?php $form->end(); ?>
</div>
