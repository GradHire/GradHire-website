<?php


use app\src\core\FPDF\FPDF;
use app\src\model\Auth;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\ServiceAccueilRepository;
use app\src\model\repository\TuteurRepository;

$etudiant = $_SESSION["simulateurEtu"];
$offre = $_SESSION['simulateurCandidature'];
$accueil = new ServiceAccueilRepository();
$accueil = $accueil->getFullByEntrepriseNom($_SESSION['idEntreprise'], $_SESSION['accueil']);
$entreprise = (new EntrepriseRepository([]))->getByIdFull($_SESSION['idEntreprise']);
$idtuteur = $_SESSION['idTuteur'];
$tuteur = (new TuteurRepository([]))->getByIdFull($idtuteur);
$signataire = $_SESSION['signataire'];

$filename = 'convention_stage_' . $etudiant["numEtudiant"] . '.pdf';
while (file_exists('uploads/Pstage/' . $filename)) {
    $filename = 'convention_stage_' . $etudiant["numEtudiant"] . '_' . rand(0, 1000) . '.pdf';
}
ob_start();

$numeroConvention = "XXXXX";
$etudiantNom = $etudiant["prenom"] . " " . $etudiant["nom"];
$etudiantNumero = $etudiant["numEtudiant"];
$ufr = "UFR - TE2 - I.U.T. de Montpellier Sete";
$typeStage = "Formation Initiale - " . $offre["typeStage"];
$themeStage = $offre["Thématique"];
$sujetStage = $offre["Sujet"];
$fonctionsTaches = $offre["fonction"];
$competences = $offre["competence"];
$periodeStage = "Du " . $offre["dateDebut"] . " au " . $offre["dateFin"];
$interruption = $offre["interruption"];
if ($interruption == "Oui") {
    $periodeStage .= " (avec interruption du " . $offre["dateDebutInterruption"] . " au " . $offre["dateFinInterruption"] . ")";
}
$dureeTravail = "Temps Plein sur" . $offre["nbJour"] . "jour(s)/semaine";
$commentairesTravail = $offre["commentairetravail"];
$langueConvention = "Francais (stage en France)";
$gratification = $offre["gratification"];
if ($gratification == "Oui") {
    $gratification .= " (" . $offre["montant"] . " par " . $offre["heureoumois"] . ")";
    $gratification .= " - en " . $offre["modalite"];
}
$origineStage = "Candidature spontanee";
$confidentialiteSujet = $offre["confconvention"];
$heuresHebdomadaires = $offre["nbHeure"] . " heures";
$modaliteSuivi = $offre["modalsuivi"];
$modaliteVersementGratification = $offre["modalite"];
$avantagesNature = $offre["avantage"];
$travailApresStage = "Rapport de Stage";
$modaliteValidationStage = "Soutenance";
$dureeStage = "392 heures de presence effective dans l organisme d accueil (representant une duree totale de 2 mois 12 jour(s) et 0 heure(s))";
$dureeStage = $offre["duree"] . " heures de presence effective dans l'organisme d'accueil";
$enseignantReferent = $_SESSION["prenomProf"] . " " . $_SESSION["nomProf"];
$etablissementAccueil = $entreprise->getNomutilisateur() . "\n" . $entreprise->getAdresse() . " " . $entreprise->getCodePostal() . " " . $entreprise->getVille() . " " . $entreprise->getPays();
$lieuStage = $accueil->getNomService() . "\n" . $accueil->getAdresse() . " " . $accueil->getCodePostal() . " " . $accueil->getCommune() . " " . $accueil->getPays();
$tuteurProfessionnel = $tuteur->getNomutilisateur() . " " . $tuteur->getPrenom();
$coordonneesTuteur = $tuteur->getEmailutilisateur();
$adresseEtudiant = $etudiant["adresse"] . " " . $etudiant["codePostal"] . " " . $etudiant["ville"] . " France";
$telephoneEtudiant = $etudiant["telephone"];
$mailEtudiant = $etudiant["emailUniv"] . " " . $etudiant["emailPerso"];
$affiliationSecuriteSociale = "Caisse d'assurance maladie " . $etudiant["CPAM"];

$content = "




N° convention: 
Etudiant: 

Type de stage: 
Thématique du stage: 
Sujet du stage: 
Fonctions et tâches: 
Compétences à acquérir ou à développer: 
Période de stage: 
Interruption du stage: 
Durée de travail: 
Commentaires sur le temps de travail: 
Langue d'impression de la convention: 
Gratification au cours du stage: 
Origine du stage: 
Confidentialité du sujet/theme du stage: 
Nombre d'heures hebdomadaires: 
Modalité de suivi du stagiaire par l'établissement: 
Modalité de versement de la gratification: 
Liste des avantages en nature: 
Nature du travail à fournir suite au stage: 
Modalité de validation du stage: 
Durée du stage: 
Enseignant référent: 
Etablissement d'accueil: 
Lieu de stage: 
Tuteur professionnel: 
Coordonnées du tuteur professionnel: 
Signataire: 
Adresse de l'étudiant: 
Téléphone de l'étudiant: 
Courriel de l'étudiant: 
Affiliation à la Sécurité Sociale: 
";

$orientation = 'P';
$format = 'A4';
$fontFamily = 'Arial';
$fontSize = 8;

$pdf = new FPDF($orientation, 'mm', $format);
$pdf->AddPage();

$pdf->SetFont($fontFamily, '', $fontSize);
$pdf->Image('resources/images/logouniversitemtp.png', 5, 5, 50, 20);
$pdf->Image('resources/images/logoiutmtp.png', 60, 5, 50, 20);
$pdf->Image('resources/images/fond-gris-moyen.jpg', 5, 35, 190, 230);
$pdf->Image('resources/images/plain-white-background-1480544970glP.jpg', 75, 37, 115, 226);

$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $content));

$x = 77;
$y = 43;

$espacementCellule = 6;

$pdf->SetXY($x, $y);
$pdf->Cell(0, 0, $numeroConvention, 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule);
$pdf->Cell(0, 0, "$etudiantNom (N Etudiant: $etudiantNumero)", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 2);
$pdf->Cell(0, 0, $ufr, 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 3);
$pdf->Cell(0, 0, "$typeStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 4);
$pdf->Cell(0, 0, "$themeStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 5);
$pdf->Cell(0, 0, "$sujetStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 6);
$pdf->Cell(0, 0, "$fonctionsTaches", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 7);
$pdf->Cell(0, 0, "$competences", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 8);
$pdf->Cell(0, 0, "$periodeStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 9);
$pdf->Cell(0, 0, "$interruption", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 10);
$pdf->Cell(0, 0, "$dureeTravail", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 11);
$pdf->Cell(0, 0, "$commentairesTravail", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 12);
$pdf->Cell(0, 0, "$langueConvention", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 13);
$pdf->Cell(0, 0, "$gratification", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 14);
$pdf->Cell(0, 0, "$origineStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 15);
$pdf->Cell(0, 0, "$confidentialiteSujet", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 16);
$pdf->Cell(0, 0, "$heuresHebdomadaires", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 17);
$pdf->Cell(0, 0, "$modaliteSuivi", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 18);
$pdf->Cell(0, 0, "$modaliteVersementGratification", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 19);
$pdf->Cell(0, 0, "$avantagesNature", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 20);
$pdf->Cell(0, 0, "$travailApresStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 21);
$pdf->Cell(0, 0, "$modaliteValidationStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 22);
$pdf->Cell(0, 0, "$dureeStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 23);
$pdf->Cell(0, 0, "$enseignantReferent", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 24);
$pdf->Cell(0, 0, "$etablissementAccueil", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 25);
$pdf->Cell(0, 0, "$lieuStage", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 26);
$pdf->Cell(0, 0, "$tuteurProfessionnel", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 27);
$pdf->Cell(0, 0, "$coordonneesTuteur", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 28);
$pdf->Cell(0, 0, "$signataire", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 29);
$pdf->Cell(0, 0, "$adresseEtudiant", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 30);
$pdf->Cell(0, 0, "$telephoneEtudiant", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 31);
$pdf->Cell(0, 0, "$mailEtudiant", 0, 1, 'L');
$pdf->SetXY($x, $y + $espacementCellule * 32);
$pdf->Cell(0, 0, "$affiliationSecuriteSociale", 0, 1, 'L');
$pdf->Output($filename, 'D', true);

ob_end_flush();
$savePath = 'uploads/Pstage/';
$pdf->Output($savePath . $filename, 'F', true);

?>
