<?php

use app\src\model\repository\ServiceAccueilRepository;
use app\src\model\repository\TuteurRepository;

$etudiant = $_SESSION['simulateurEtu'];
$offre = $_SESSION['simulateurCandidature'];
$accueil = new ServiceAccueilRepository();
$accueil = $accueil->getFullByEntrepriseNom($_SESSION['idEntreprise'], $_SESSION['accueil']);
$idtuteur = $_SESSION['idTuteur'];
$tuteur = (new TuteurRepository([]))->getByIdFull($idtuteur);
$signataire = $_SESSION['signataire'];
?>
<div class="w-full flex justify-center mt-5">
    <div class="grid grid-cols-2 gap-4">
        <div class="font-bold">
            <p class="mb-2">N°Etudiant</p>
            <p class="mb-2">Nom</p>
            <p class="mb-2">Prénom</p>
            <p class="mb-2">Adresse</p>
            <p class="mb-2">Téléphone</p>
            <p class="mb-2">Mail institutionnel</p>
            <p class="mb-2">Mail personnel</p>
            <p class="mb-2">CPAM et adresse postale</p>

            <p class="mb-2">Type de stage</p>
            <p class="mb-2">Thématique du stage</p>
            <p class="mb-2">Sujet</p>
            <p class="mb-2">Fonctions et tâches</p>
            <p class="mb-2">Compétences</p>

            <p class="mb-2">Date de début du stage</p>
            <p class="mb-2">Date de fin du stage</p>
            <p class="mb-2">Interruption au cours du stage</p>
            <?php
            if ($offre["interruption"] == "oui") {
                echo "<p class='mb-2'>Date de début de l'interruption</p>";
                echo "<p class='mb-2'>Date de fin de l'interruption</p>";
            }
            ?>
            <p class="mb-2">Durée effective du stage</p>
            <p class="mb-2">Jours de travail hebdo</p>
            <p class="mb-2">Commentaire tps travail</p>
            <p class="mb-2">Nombre d'heures hebdomadaires</p>
            <p class="mb-2">Nombre de jours de congés autorisés</p>

            <p class="mb-2">Gratification au cours du stage ?</p>
            <?php
            if ($offre["gratification"] == "oui") {
                echo "<p class='mb-2'>Montant de la gratification</p>";
                echo "<p class='mb-2'>Modalité de versement</p>";
            }
            ?>

            <p class="mb-2">Origine du stage</p>
            <p class="mb-2">Confidentialité sujet/theme</p>
            <p class="mb-2">Modalité de suivi du stagiaire</p>
            <p class="mb-2">Liste des avantages en nature</p>

            <p class="mb-2">Enseignant Référent</p>

            <p class="mb-2">Etablissement d'accueil</p>
            <p class="mb-2">Lieu du stage</p>
            <p class="mb-2">Tuteur professionnel</p>
            <p class="mb-2">Coordonnées tuteur professionnel</p>
            <p class="mb-2">Signataire</p>
        </div>
        <div>
            <?php
            echo "<p class='mb-2'>" . $etudiant["numEtudiant"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["nom"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["prenom"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["adresse"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["telephone"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["emailUniv"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["emailPerso"] . "</p>";
            echo "<p class='mb-2'>" . $etudiant["CPAM"] . "</p>";

            echo "<p class='mb-2'>" . $offre["typeStage"] . "</p>";
            echo "<p class='mb-2'>" . $offre["Thématique"] . "</p>";
            echo "<p class='mb-2'>" . $offre["Sujet"] . "</p>";
            echo "<p class='mb-2'>" . $offre["fonction"] . "</p>";
            echo "<p class='mb-2'>" . $offre["competence"] . "</p>";

            echo "<p class='mb-2'>" . $offre["dateDebut"] . "</p>";
            echo "<p class='mb-2'>" . $offre["dateFin"] . "</p>";
            echo "<p class='mb-2'>" . $offre["interruption"] . "</p>";
            if ($offre["interruption"] == "oui") {
                echo "<p class='mb-2'>" . $offre["dateDebutInterruption"] . "</p>";
                echo "<p class='mb-2'>" . $offre["dateFinInterruption"] . "</p>";
            }
            echo "<p class='mb-2'>" . $offre["duree"] . "</p>";
            echo "<p class='mb-2'>" . $offre["nbJour"] . "</p>";
            echo "<p class='mb-2'>" . $offre["commentairetravail"] . "</p>";
            echo "<p class='mb-2'>" . $offre["nbHeure"] . "</p>";
            echo "<p class='mb-2'>" . $offre["nbjourConge"] . "</p>";

            echo "<p class='mb-2'>" . $offre["gratification"] . "</p>";
            if ($offre["gratification"] == "oui") {
                echo "<p class='mb-2'>" . $offre["montant"] . " par " . $offre["heureoumois"] . "</p>";
                echo "<p class='mb-2'>" . $offre["modalite"] . "</p>";
            }

            echo "<p class='mb-2'>" . $offre["commenttrouve"] . "</p>";
            echo "<p class='mb-2'>" . $offre["confconvention"] . "</p>";
            echo "<p class='mb-2'>" . $offre["modalsuivi"] . "</p>";
            echo "<p class='mb-2'>" . $offre["avantage"] . "</p>";

            echo "<p class='mb-2'>" . $_SESSION["nomProf"] . " " . $_SESSION["prenomProf"] . "</p>";

            echo "<p class='mb-2'>" . $accueil->getNomService() . "</p>";
            echo "<p class='mb-2'>" . $accueil->getAdresse() . $accueil->getCodePostal() . $accueil->getCommune() . "</p>";
            echo "<p class='mb-2'>" . $tuteur->getNomutilisateur() . " " . $tuteur->getPrenom() . "</p>";
            echo "<p class='mb-2'>" . $tuteur->getEmailutilisateur() . "</p>";
            echo "<p class='mb-2'>" . $signataire . "</p>";
            ?>

        </div>
        <a href="validersimulation"
           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Valider simulation</a>

        <a href="simulateur"
           class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Recommencer</a>
    </div>


</div>

