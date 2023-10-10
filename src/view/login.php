<?php

/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

	<h1>Login</h1>

	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->field("username");
		$form->field("password");
		$form->field("remember");
		$form->submit("Se connecter");
		echo $form->getError();
		?>
	</div>
	<?php $form->end(); ?>
</div>
