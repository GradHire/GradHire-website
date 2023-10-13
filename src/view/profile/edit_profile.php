<?php
/** @var $form FormModel */

use app\src\model\Application;
use app\src\model\Form\FormModel;
$this->title = 'Profile';

?>
<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

	<h1>Modifier profile</h1>

	<?php $form->start(); ?>
	<div class="w-full gap-4 flex flex-col">

		<div class="rounded-full overflow-hidden h-20 w-20 ml-2">
			<img src="<?= Application::getUser()->get_picture() ?>" alt="Photo de profil" id="preview"
			     class="w-full h-full object-cover rounded-full"/>
		</div>
		<?php
		$form->print_all_fields();
		$form->submit("Update");
		$form->getError();
		?>
	</div>
	<?php $form->end(); ?>
	<script>
        const imageInput = document.getElementById("image");
        const imagePreview = document.getElementById("preview");

        imageInput.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove("hidden");
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "";
                imagePreview.classList.add("hidden");
            }
        });
	</script>
</div>
