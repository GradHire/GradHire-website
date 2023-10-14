<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>
<div class="w-full pt-12 pb-24 gap-4 flex flex-col">

	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->print_all_fields();
		$form->submit("Créer compte");
		$form->getError();
		?>
	</div>
	<?php $form->end(); ?>
</div>
