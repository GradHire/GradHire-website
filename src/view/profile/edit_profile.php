<?php
/** @var $form FormModel */

use app\src\model\Form\FormFile;
use app\src\model\Form\FormModel;
$this->title = 'Profile';

?>
<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

	<h1>Modifier profile</h1>

	<?php $form->start("multipart/form-data"); ?>
	<div class="w-full gap-4 flex flex-col">
		<?php
		FormFile::New("picture")->accept(".jpg,.png,.jpeg")->render();
		$form->print_all_fields();
		$form->submit("Update");
		echo $form->getError();
		?>
	</div>
	<?php $form->end(); ?>
</div>
