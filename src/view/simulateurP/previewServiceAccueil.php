<?php

use app\src\model\repository\ServiceAccueilRepository;

$accueil = new ServiceAccueilRepository();

$accueil = $accueil->getFullByEntrepriseNom($_SESSION['idEntreprise'], $_SESSION['accueil']);

?>
<div class="w-full flex justify-center mt-5">
    <div class="grid grid-cols-2 gap-4">
        <div class="font-bold">
            <p class="mb-2">Nom Accueil</p>
            <p class="mb-2">Voie</p>
            <p class="mb-2">Code Postal</p>
            <p class="mb-2">Commune</p>
            <p class="mb-2">Pays</p>
        </div>
        <div>
            <?php
            echo "<p class='mb-2'>" . $accueil->getNomService() . "</p>";
            echo "<p class='mb-2'>" . $accueil->getAdresse() . "</p>";
            echo "<p class='mb-2'>" . $accueil->getCodePostal() . "</p>";
            echo "<p class='mb-2'>" . $accueil->getCommune() . "</p>";
            echo "<p class='mb-2'>" . $accueil->getPays() . "</p>";
            ?>
        </div>
    </div>
</div>

<div class="w-full flex justify-center gap-4 mt-4">
    <button type="button" id="modifyButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
        Modifier
    </button>
    <button type="button" id="confirmButton" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
        Confirmez
    </button>
</div>


<script>
    document.getElementById('confirmButton').addEventListener('click', function () {
        window.location.href = 'simulateurTuteur';
    });
</script>
<script>
    document.getElementById('modifyButton').addEventListener('click', function () {
        window.location.href = 'simulateurServiceAccueil';
    });
</script>
