<?php
/** @var $array array */

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Confirmez les données saisies :</h1>

    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        foreach ($array as $key => $value) {
            echo "<p id='el-$key'>" . $key . " : " . $value . "</p>";
        }
        ?>
        <button type="button" id="modifyButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
            Modifier
        </button>
        <button type="button" id="confirmButton" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
            Confirmez
        </button>
    </div>
    <script>
        document.getElementById('confirmButton').addEventListener('click', function () {
            if (document.getElementById('el-typeStage') != null) {
                window.location.href = 'simulateurProfReferent';
            } else if (document.getElementById('el-numEtudiant') != null) {
                window.location.href = 'listEntreprise';
            } else if (document.getElementById('el-Effectif') != null) {
                window.location.href = 'simulateurServiceAccueil';
            } else if (document.getElementById('el-Nom Accueil') != null) {
                window.location.href = 'simulateurTuteur';
            } else if (document.getElementById('el-Nom Signataire') != null) {
                window.location.href = 'visuRecapConv';
            }
        });
    </script>
    <script>
        document.getElementById('modifyButton').addEventListener('click', function () {
            history.back();
        });
    </script>
