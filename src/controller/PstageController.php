<?php

namespace app\src\controller;

use app\src\model\Application;
use app\src\model\dataObject\ServiceAccueil;
use app\src\model\Form\FormModel;
use app\src\model\ImportPstage;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\ServiceAccueilRepository;
use app\src\model\Request;


class PstageController extends AbstractController
{
    public function importercsv(Request $request): string
    {

        $form = new FormModel([
            "file" => FormModel::file("File")->required()->accept(["csv"])
        ]);
        $form->useFile();

        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $path = "Import/";
                if (!$form->getFile("file")->save($path, "file")) {
                    $form->setError("Impossible de télécharger tous les fichiers");
                    return '';
                }
            }
            $path = fopen("Import/file.csv", "r");
            $i = 0;
            $importer = new ImportPstage();
            while (($data = fgetcsv($path, 100000, ";")) !== FALSE) {

                $num = count($data);
                if ($i == 0) {
                    $i++;
                    continue;
                }
                if ($num != 82) break;
                $importer->importerligne($data);
            }
        }
        return $this->render('Import', [
            'form' => $form
        ]);
    }

    public function simulateur(Request $request): string
    {
        $id = Application::getUser()->getId();
        $etudiant = (new EtudiantRepository([]))->getByIdFull($id);
        $formData = $_SESSION['simulateurEtu'] ?? [];

        $form = new FormModel([
            "numEtudiant" => FormModel::string("Numéro étudiant")->required()->min(8)->max(8)->default($formData['numEtudiant'] ?? $etudiant->getNumEtudiant()),
            "nom" => FormModel::string("Nom")->required()->default($formData['nom'] ?? $etudiant->getNomutilisateur()),
            "prenom" => FormModel::string("Prénom")->required()->default($formData['prenom'] ?? $etudiant->getPrenom()),
            "adresse" => FormModel::string("Adresse")->required()->default($formData['adresse'] ?? $etudiant->getAdresse()),
            "codePostal" => FormModel::string("Code postal")->required()->length(5)->default($formData['codePostal'] ?? $etudiant->getCodePostal()),
            "ville" => FormModel::string("Ville")->required()->default($formData['ville'] ?? $etudiant->getNomVille()),
            "telephone" => FormModel::phone("Téléphone")->default($formData['telephone'] ?? $etudiant->getNumtelutilisateur()),
            "emailPerso" => FormModel::email("Email perso")->required()->default($formData['emailPerso'] ?? $etudiant->getEmailPerso()),
            "emailUniv" => FormModel::email("Email universitaire")->required()->default($formData['emailUniv'] ?? $etudiant->getEmailutilisateur()),
            "CPAM" => FormModel::string("CPAM et Adresse postal")->required()->default($formData['CPAM'] ?? ""),
            "anneeUni" => FormModel::select("Année universitaire", ["2023-2024" => "2023-2024", "2024-2025" => "2024-2025", "2025-2026" => "2025-2026"])->required()->default($formData['anneeUni'] ?? null),
            "nbHeure" => FormModel::int("Nombre d'heure")->required()->default($formData['nbHeure'] ?? 1)->min(1)
        ]);

        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $_SESSION['simulateurEtu'] = $form->getParsedBody();
                return $this->render('simulateurP/previewetu', ['form' => $form]);
            }
        }
        return $this->render('simulateurP/simulateuretu', ['form' => $form]);
    }


    public function simulateurOffre(Request $request): string
    {
        $form2 = $this->getModel();
        if ($request->getMethod() === 'post') {
            $listEntreprise = [];
            $formData = $request->getBody();
            $typeRecherche = $formData['typeRecherche'];
            $ent = new EntrepriseRepository([]);
            if ($typeRecherche == "nomEnt") {
                $listEntreprise = $ent->getByName($formData['nomEnt'], $formData['pays'], $formData['department']);
            } else if ($typeRecherche == "numsiret") {
                $listEntreprise = $ent->getBySiret($formData['siret'], $formData['siren']);
            } else if ($typeRecherche == "numTel") {
                $listEntreprise = $ent->getByTel($formData['tel'], $formData['fax']);
            } else if ($typeRecherche == "adresse") {
                $listEntreprise = $ent->getByAdresse($formData['adresse'], $formData['codePostal'], $formData['pays']);
            }
            return $this->render('simulateurP/listEntreprise', ['form' => $form2, 'listEntreprise' => $listEntreprise]);
        }

        return $this->render('simulateurP/simulateurOffre', ['form2' => $form2]);

    }

    /**
     * @return FormModel
     */
    public function getModel(): FormModel
    {
        $form2 = new FormModel([
            "typeRecherche" => FormModel::select("Type de recherche", ["nomEnt" => "Nom de l'entreprise", "numsiret" => "Numéro Siret", "numTel" => "Tèl/Fax", "adresse" => "adresse"])->required()->default("nomEnt"),
            "nomEnt" => FormModel::string("Nom de l'entreprise")->default("")->required(),
            "pays" => FormModel::select("Pays", ["France" => "France", "Allemagne" => "Allemagne", "Angleterre" => "Angleterre", "Espagne" => "Espagne", "Italie" => "Italie", "Portugal" => "Portugal", "Suisse" => "Suisse", "Autre" => "Autre"])->required(),
            "department" => FormModel::string("Département")->default("")->length(2)->required(),
            "siret" => FormModel::string("Numéro Siret")->default("")->length(14),
            "siren" => FormModel::string("Numéro Siren")->default("")->length(9),
            "tel" => FormModel::phone("Téléphone")->default(""),
            "fax" => FormModel::phone("Fax")->default(""),
            "adresse" => FormModel::string("Adresse")->default(""),
            "codePostal" => FormModel::string("Code postal")->default("")->length(5)
        ]);
        return $form2;
    }

    public function previewOffre(Request $request): string
    {
        $id = $_GET['idEntreprise'];
        $_SESSION["idEntreprise"] = $id;
        return $this->render('simulateurP/previewOffre', ['id' => $id]);
    }

    public function creerEntreprise(Request $request): string
    {
        $form = new FormModel([
            "nomEnt" => FormModel::string("Nom de l'entreprise *")->required(),
            "effectif" => FormModel::select("Effectif *", ["0" => "0", "1 à 9" => "1 à 9", "10 à 49" => "10 à 49", "50 à 199" => "50 à 199", "200 à 999" => "200 à 999", "1000 et +" => "1000 et +"])->required()->default("0"),
            "siret" => FormModel::string("Numéro Siret *")->required()->length(14),
            "type" => FormModel::select("Type d'entreprise *", ["Administration" => "Administration", "Associations" => "Associations", "Entreprise d'insertion" => "Entreprise d'insertion", "Entreprise privée" => "Entreprise privée", "Entreprise public/SEM" => "Entreprise public/SEM", "Mutuelle/Coopérative/CE" => "Mutuelle/Coopérative/CE", "NON CONNU" => "NON CONNU", "Société civile" => "Société civile", "Société étrangère" => "Société étrangère", "Sté Cdite Actions" => "Sté Cdite Actions", "Association" => "Association", "Entreprise public / SEM" => "Entreprise public / SEM", "Mutuelle Coopérative" => "Mutuelle Coopérative", "ONG" => "ONG", "EPIC" => "EPIC", "Syndicat mixte" => "Syndicat mixte"])->required(),
            "codeNaf" => FormModel::string("Code NAF *")->required()->length(5),
            "activite" => FormModel::string("Activité Principale")->default(""),
            "voie" => FormModel::string("Adresse Voie*")->required(),
            "residence" => FormModel::string("Bâtiment / Résidence / Z.I.")->default(""),
            "cedex" => FormModel::string("Cedex")->default(""),
            "codePostal" => FormModel::string("Code postal *")->required()->length(5),
            "ville" => FormModel::string("Ville *")->required(),
            "pays" => FormModel::select("Pays *", ["France" => "France", "Allemagne" => "Allemagne", "Angleterre" => "Angleterre", "Espagne" => "Espagne", "Italie" => "Italie", "Portugal" => "Portugal", "Suisse" => "Suisse", "Autre" => "Autre"])->required(),
            "email" => FormModel::email("Email")->default(""),
            "web" => FormModel::string("Site web")->default(""),
            "tel" => FormModel::phone("Téléphone *")->required(),
            "fax" => FormModel::string("Fax")->default("")
        ]);
        if ($request->getMethod() === 'post') {
            $form->setError("Impossible de créer l'entreprise");
            if ($form->validate($request->getBody())) {
                $formData = $form->getParsedBody();
                $ent = new EntrepriseRepository([]);
                $ent->create($formData['nomEnt'], $formData['email'], $formData['tel'], "", $formData['type'], $formData['effectif'], $formData['codeNaf'], $formData['fax'], $formData['web'], $formData['voie'], $formData['cedex'], $formData['residence'], $formData['codePostal'], $formData['pays'], $formData['ville'], $formData['siret']);
                $form2 = $this->getModel();
                return $this->render('simulateurP/simulateurOffre', ['form2' => $form2]);
            }
        }
        return $this->render('simulateurP/creerEntreprise', ['form' => $form]);
    }

    public function simulateurServiceAccueil(Request $request)
    {
        $serviceaccueil = (new ServiceAccueilRepository())->getFullByEntreprise($_SESSION["idEntreprise"]);
        $serviceaccueil["Non renseigné"] = "Non renseigné";
        $form = new FormModel([
            "accueil" => FormModel::select("Accueil", $serviceaccueil)->required()->default("Non renseigné"),
        ]);
        return $this->render('simulateurP/simulateurServiceAccueil', ['form' => $form]);
    }

    public function creerService(Request $request)
    {
        $form = new FormModel([
            "nomService" => FormModel::string("Nom du service")->required(),
            "memeAdresse" => FormModel::radio("Adresse identique à l'entreprise", ["Oui" => "Oui", "Non" => "Non"])->required()->default("Oui")->id("memeAdresse"),
            "voie" => FormModel::string("Adresse Voie")->id("voie"),
            "residence" => FormModel::string("Bâtiment / Résidence / Z.I.")->default(""),
            "tel" => FormModel::phone("Téléphone"),
            "cp" => FormModel::string("Code postal")->length(5)->id("cp"),
            "ville" => FormModel::string("Ville")->id("ville"),
            "pays" => FormModel::select("Pays", ["France" => "France", "Allemagne" => "Allemagne", "Angleterre" => "Angleterre", "Espagne" => "Espagne", "Italie" => "Italie", "Portugal" => "Portugal", "Suisse" => "Suisse", "Autre" => "Autre"])->id("pays")
        ]);
        return $this->render('simulateurP/creerService', ['form' => $form]);
    }


}