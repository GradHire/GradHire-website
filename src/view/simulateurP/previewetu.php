<?php
/** @var $form FormModel */

use app\src\model\Form\FormModel;

?>
<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">

    <h1 class="text-3xl font-bold text-center">Confirmez les données saisies :</h1>
    <?php
    $array = $form->getParsedBody();
    $_SESSION['simulateurEtu'] = $array;
    ?>

    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        foreach ($array as $key => $value) {
            echo "<p>" . $key . " : " . $value . "</p>";
        }
        ?>
        <button type="button" id="modifyButton">Modifier</button>
        <button type="button" id="confirmButton">Confirmez</button>

    </div>
    <script>
        document.getElementById('confirmButton').addEventListener('click', function () {
            window.location.href = 'simulateurOffre';
        });
    </script>
    <script>
        document.getElementById('modifyButton').addEventListener('click', function () {
            window.location.href = 'simulateur';
        });
    </script>