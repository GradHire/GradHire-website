<?php
/* @var $vueChemin */
/* @var $nom */
?>
<div class="flex w-full mt-3 ml-10">
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
        if ($vueChemin == "simulateur.php" && $nom == "Etudiant") {
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
        if ($vueChemin == "listEntreprise.php" || ($vueChemin == "preview.php" && isset($array["Nom Entreprise"])) || $vueChemin == "creerEntreprise.php") {
            ?>
            <p class="text-blue-500">Entreprise</p>
            <?php
        } else if (isset($_SESSION["idEntreprise"])) {
            ?>
            <a href="listEntreprise">Entreprise</a>
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
        if (($vueChemin == "simulateur.php" && $nom == "Service") || ($vueChemin == "creer.php" && $nom == "Créer un service d'accueil") || ($vueChemin == "preview.php" && isset($array["Nom Accueil"]))) {
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
        if (($vueChemin == "listTuteur.php") || ($vueChemin == "creer.php" && $nom == "Créer un tuteur")) {
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
        if ($vueChemin == "simulateurCandidature.php" || isset($_GET["idTuteur"])) {
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
        if ($vueChemin == "listProf.php") {
            ?>
            <p class="text-blue-500">Professeur référent</p>
            <?php
        } else if (isset($_SESSION["idProfRef"])) {
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
        if (($vueChemin == "simulateur.php" && $nom == "Signataire") || ($vueChemin == "creer.php" && $nom == "Créer un signataire") || ($vueChemin == "preview.php" && isset($array["Nom Signataire"]))) {
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
        } else if (isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"]) && isset($_SESSION["simulateurCandidature"]) && isset($_SESSION["idProfRef"]) && isset($_SESSION["signataire"])) {
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
<?php
require __DIR__ . "/{$vueChemin}";
?>
