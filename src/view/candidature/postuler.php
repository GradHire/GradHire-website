<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

	<h1>Postuler</h1>

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
