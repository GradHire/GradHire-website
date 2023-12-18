<?php
/** @var $array array */

?>

<div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto mx-auto max-w-md">

    <h1 class="text-3xl font-bold text-center">Confirmez les données saisies :</h1>

    <div class="w-full gap-4 flex flex-col" id="step1">
        <?php
        foreach ($array as $key => $value) {
            //remove spaces
            $k = str_replace(' ', '', $key);
            echo "<p id='el-$k'>" . $key . " : " . $value . "</p>";
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
        const elements = {
            "el-typeStage": "simulateurProfReferent",
            "el-numEtudiant": "listEntreprise",
            "el-Effectif": "simulateurServiceAccueil",
            "el-NomAccueil": "simulateurTuteur",
            "el-NomSignataire": "visuRecapConv"
        }
        document.getElementById('confirmButton').addEventListener('click', function () {
            for (const [key, value] of Object.entries(elements))
                if (document.getElementById(key) != null)
                    window.location.href = value;
        });
    </script>
    <script>
        document.getElementById('modifyButton').addEventListener('click', function () {
            history.back();
        });
    </script>
