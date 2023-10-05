<?php

/** @var $model \app\src\model\LoginForm */

use app\src\core\component\form\Form;

?>

<div class="w-full md:max-w-[75%] gap-4 flex flex-col">

    <h1>Login</h1>

    <?php $form = Form::begin('', 'post') ?>
    <div class="w-full gap-4 flex flex-col">

    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <button class="text-white bg-zinc-700 hover:bg-zinc-800 focus:ring-4 focus:outline-none focus:ring-zinc-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-zinc-600 dark:hover:bg-zinc-700 dark:focus:ring-zinc-800">
        Submit
    </button>
    </div>
    <?php Form::end() ?>
</div>
