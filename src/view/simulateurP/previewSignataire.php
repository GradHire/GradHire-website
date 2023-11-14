<?php

use app\src\model\repository\SignataireRepository;

$signataire = (new SignataireRepository())->getFullByEntrepriseNom($_SESSION['signataire'], $_SESSION["idEntreprise"]);
?>
<div class="w-full flex justify-center mt-5">
    <div class="grid grid-cols-2 gap-4">
        <div class="font-bold">
            <p class="mb-2">Nom Signataire</p>
            <p class="mb-2">Prénom Signataire</p>
            <p class="mb-2">Fonction Signataire</p>
            <p class="mb-2">MailSignataire</p>
        </div>
        <div>
            <?php
            echo "<p class='mb-2'>" . $signataire->getNomSignataire() . "</p>";
            echo "<p class='mb-2'>" . $signataire->getPrenomSignataire() . "</p>";
            echo "<p class='mb-2'>" . $signataire->getFonctionSignataire() . "</p>";
            echo "<p class='mb-2'>" . $signataire->getMailSignataire() . "</p>";
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
        window.location.href = 'visuRecapConv';
    });
</script>
<script>
    document.getElementById('modifyButton').addEventListener('click', function () {
        window.location.href = 'simulateurSignataire';
    });
</script>
