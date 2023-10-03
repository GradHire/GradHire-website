<?php

/** @var $model \app\models\LoginForm */

use app\core\form\Form;

?>

<div class="w-full md:max-w-[50%] gap-4 flex flex-col">

    <h1>Login</h1>

    <?php $form = Form::begin('', 'post') ?>
    <div class="w-full gap-4 flex flex-col">

    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <button class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
        Submit
    </button>
    </div>
    <?php Form::end() ?>
</div>
