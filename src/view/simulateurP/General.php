<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<nav>
    <div class="flex w-full mt-3 ">
        <div class="">
            <?php
            if ($vueChemin == "explicationSimu.php") {
                ?>
                <p class="text-blue-500">Explication</p>
                <?php
            } else {
                ?>
                <a href="explicationSimu">Explication</a>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateuretu.php" || $vueChemin == "previewetu.php") {
                ?>
                <p class="text-blue-500">Etudiant</p>
                <?php
            } else if (isset($_SESSION["simulateurEtu"])) {
                ?>
                <a href="simulateur">Etudiant</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Etudiant</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateurOffre.php" || $vueChemin == "previewOffre.php" || $vueChemin == "creerEntreprise.php" || $vueChemin == "listEntreprise.php") {
                ?>
                <p class="text-blue-500">Entreprise</p>
                <?php
            } else if (isset($_SESSION["idEntreprise"])) {
                ?>
                <a href="simulateurOffre">Entreprise</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Entreprise</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateurServiceAccueil.php" || $vueChemin == "previewServiceAccueil.php" || $vueChemin == "creerService.php") {
                ?>
                <p class="text-blue-500">Service d'accueil</p>
                <?php
            } else if (isset($_SESSION["accueil"])) {
                ?>
                <a href="simulateurServiceAccueil">Service d'accueil</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Service d'accueil</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateurTuteur.php" || $vueChemin == "creerTuteur.php" || $vueChemin == "previewTuteur.php") {
                ?>
                <p class="text-blue-500">Tuteur</p>
                <?php
            } else if (isset($_SESSION["idTuteur"])) {
                ?>
                <a href="simulateurTuteur">Tuteur</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Tuteur</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateurCandidature.php" || $vueChemin == "previewCandidature.php") {
                ?>
                <p class="text-blue-500">Stage</p>
                <?php
            } else if (isset($_SESSION["simulateurCandidature"])) {
                ?>
                <a href="simulateurCandidature">Stage</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Stage</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateurProfReferent.php" || $vueChemin == "listProf.php") {
                ?>
                <p class="text-blue-500">Professeur référent</p>
                <?php
            } else if (isset($_SESSION["nomProf"])) {
                ?>
                <a href="simulateurProfReferent">Professeur référent</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Professeur référent</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "simulateurSignataire.php" || $vueChemin == "creerSignataire.php" || $vueChemin == "previewSignataire.php") {
                ?>
                <p class="text-blue-500">Signataire</p>
                <?php
            } else if (isset($_SESSION["signataire"])) {
                ?>
                <a href="simulateurSignataire">Signataire</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Signataire</p>
                <?php
            }
            ?>
        </div>
        <div class="ml-5">
            <?php
            if ($vueChemin == "visuRecapConv.php") {
                ?>
                <p class="text-blue-500">Récapitulatif</p>
                <?php
            } else if (isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"]) && isset($_SESSION["simulateurCandidature"]) && isset($_SESSION["nomProf"]) && isset($_SESSION["signataire"])) {
                ?>
                <a href="visuRecapConv">Récapitulatif</a>
                <?php
            } else {
                ?>
                <p class="text-gray-500">Récapitulatif</p>
                <?php
            }
            ?>
        </div>
    </div>
</nav>
<?php
require __DIR__ . "/{$vueChemin}";
?>
</body>
</html>
