<?php
/** @var $id int */

use app\src\model\repository\TuteurRepository;

?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24">

    <h1 class="text-3xl font-bold text-center">Confirmez les données de l'établissement d'accueil :</h1>
    <?php
    $tuteur = (new TuteurRepository([]))->getByIdFull($id);
    ?>

    <div class="w-full gap-4 flex flex-col" id="step1">
        <div class="grid grid-cols-2 ">
            <div class="font-bold">
                <p>Nom Entreprise</p>
                <p>Type d'établissement</p>
                <p>Effectif</p>
                <p>Siret</p>
                <p>Adresse</p>
                <p>Code Postal</p>
                <p>Ville</p>
                <p>Pays</p>
                <p>Code Naf</p>
            </div>
            <div>
                <?php
                echo "<p>" . $entreprise->getNomutilisateur() . "</p>";
                echo "<p>" . $entreprise->getTypestructure() . "</p>";
                echo "<p>" . $entreprise->getEffectif() . "</p>";
                echo "<p>" . $entreprise->getSiret() . "</p>";
                echo "<p>" . $entreprise->getAdresse() . "</p>";
                echo "<p>" . $entreprise->getCodePostal() . "</p>";
                echo "<p>" . $entreprise->getVille() . "</p>";
                echo "<p>" . $entreprise->getPays() . "</p>";
                echo "<p>" . $entreprise->getCodenaf() . "</p>";
                ?>
            </div>
        </div>
        <button type="button" id="modifyButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
            Modifier
        </button>
        <button type="button" id="confirmButton" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
            Confirmez
        </button>
    </div>

    <script>
        document.getElementById('confirmButton').addEventListener('click', function () {
            window.location.href = 'simulateurServiceAccueil';
        });
    </script>
    <script>
        document.getElementById('modifyButton').addEventListener('click', function () {
            window.location.href = 'simulateurOffre';
        });
    </script>