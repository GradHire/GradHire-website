<?php


$array = $_SESSION['simulateurCandidature'];
?>

<div class="w-full max-w-md gap-4 flex flex-col pt-12 pb-24 mx-auto max-w-md">
    <h1 class="text-3xl font-bold text-center">Confirmez les données saisies :</h1>

    <div class="w-full flex justify-center mt-5">
        <div class="grid grid-cols-2 gap-4">
            <div class="font-bold">
                <p class="mb-2">Type de stage</p>
                <p class="mb-2">Thématique de stage</p>
                <p class="mb-2">Sujet</p>
                <p class="mb-2">Fonctions et taches</p>
                <p class="mb-2">Compétences</p>
                <p class="mb-2">Date de début du stage</p>
                <p class="mb-2">Date de fin du stage</p>
                <p class="mb-2">Interruption au cours du stage</p>
                <?php
                if ($array["interruption"] === "Oui") {
                    ?>
                    <p class="mb-2">Date de début de l'interruption</p>
                    <p class="mb-2">Date de fin de l'interruption</p>
                    <?php
                }
                ?>
                <p class="mb-2">Durée effective du stage</p>
                <p class="mb-2">Nombre de jours de travail hebdomadaires</p>
                <p class="mb-2">Nombre d'heures hebdomadaires</p>
                <p class="mb-2">Nombre de jours de congés autorisés</p>
                <p class="mb-2">Commentaire temps travail</p>
                <p class="mb-2">Gratification au cours du stage ?</p>
                <?php
                if ($array["gratification"] === "Oui") {
                    ?>
                    <p class="mb-2">Montant de la gratification</p>
                    <p class="mb-2">Modalité de paiement</p>
                    <?php
                }
                ?>
                <p class="mb-2">Comment le stage a t-il était trouvé ?</p>
                <p class="mb-2">Confidentialité du sujet/theme du stage</p>
                <p class="mb-2">Modalité de suivi du stagiaire</p>
                <p class="mb-2">Liste des avantages en nature</p>
            </div>
            <div>
                <?php
                echo "<p class='mb-2'>" . $array["typeStage"] . "</p>";
                echo "<p class='mb-2'>" . $array["Thématique"] . "</p>";
                echo "<p class='mb-2'>" . $array["Sujet"] . "</p>";
                echo "<p class='mb-2'>" . $array["fonction"] . "</p>";
                echo "<p class='mb-2'>" . $array["competence"] . "</p>";
                echo "<p class='mb-2'>" . $array["dateDebut"] . "</p>";
                echo "<p class='mb-2'>" . $array["dateFin"] . "</p>";
                echo "<p class='mb-2'>" . $array["interruption"] . "</p>";
                if ($array["interruption"] === "Oui") {
                    echo "<p class='mb-2'>" . $array["dateDebutInterruption"] . "</p>";
                    echo "<p class='mb-2'>" . $array["dateFinInterruption"] . "</p>";
                }
                echo "<p class='mb-2'>" . $array["duree"] . "</p>";
                echo "<p class='mb-2'>" . $array["nbJour"] . "</p>";
                echo "<br>";
                echo "<p class='mb-2'>" . $array["nbHeure"] . "</p>";
                echo "<br>";
                echo "<p class='mb-2'>" . $array["nbjourConge"] . "</p>";
                echo "<br>";
                echo "<p class='mb-2'>" . $array["commentairetravail"] . "</p>";
                echo "<p class='mb-2'>" . $array["gratification"] . "</p>";
                echo "<br>";

                if ($array["gratification"] === "Oui") {
                    echo "<p class='mb-2'>" . $array["montant"] . " par " . $array["heureoumois"] . "</p>";
                    echo "<p class='mb-2'>" . $array["modalite"] . "</p>";
                }
                echo "<p class='mb-2'>" . $array["commenttrouve"] . "</p>";
                echo "<br>";
                echo "<p class='mb-2'>" . $array["confconvention"] . "</p>";
                echo "<br>";
                echo "<p class='mb-2'>" . $array["modalsuivi"] . "</p>";
                echo "<p class='mb-2'>" . $array["avantage"] . "</p>";

                ?>
            </div>
        </div>
    </div>
    <div class="w-full gap-4 flex flex-col">


        <button type="button" id="modifyButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
            Modifier
        </button>
        <button type="button" id="confirmButton" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
            Confirmez
        </button>

    </div>
    <script>
        document.getElementById('confirmButton').addEventListener('click', function () {
            window.location.href = 'simulateurProfReferent';
        });
    </script>
    <script>
        document.getElementById('modifyButton').addEventListener('click', function () {
            window.location.href = 'simulateurCandidature';
        });
    </script>
