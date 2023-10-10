<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>
<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

	<h1>Pro Register</h1>

	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		$form->field("name");
		$form->field("email");
		$form->field("siret");
		$form->field("phone");
		$form->field("password");
		$form->field("password2");
		$form->submit("Créer compte");
		echo $form->getError();
		?>
		<a href="pro_login">Connexion pro</a>
	</div>
	<?php $form->end(); ?>
</div>
