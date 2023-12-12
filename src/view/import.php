<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">
	<h2 class="text-xl font-bold">Import Pstage/Studea</h2>
	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->print_all_fields();
		$form->submit("Importer");
		$form->getError();
		?>
	</div>
	<?php $form->end(); ?>
</div>
