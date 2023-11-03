<?php

namespace app\src\controller;

use app\src\model\Application;
use app\src\model\dataObject\Etudiant;
use app\src\model\Form\FormModel;
use app\src\model\ImportPstage;
use app\src\model\repository\EtudiantRepository;
use app\src\model\Request;


class PstageController extends AbstractController
{
    public function importercsv(Request $request): string
    {

        $form = new FormModel([
            "file" => FormModel::file("File")->required(),
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

        $formData = $_SESSION['form_data'] ?? [];

        $form = new FormModel([
            "numEtudiant" => FormModel::string("Numéro étudiant")->required()->min(8)->max(8)->default($formData['numEtudiant'] ?? $etudiant->getNumEtudiant()),
            "nom" => FormModel::string("Nom")->required()->default($formData['nom'] ?? $etudiant->getNomutilisateur()),
            "prenom" => FormModel::string("Prénom")->required()->default($formData['prenom'] ?? $etudiant->getPrenom()),
            "adresse" => FormModel::string("Adresse")->required()->default($formData['adresse'] ?? $etudiant->getAdresse()),
            "codePostal" => FormModel::string("Code postal")->required()->min(5)->max(5)->default($formData['codePostal'] ?? $etudiant->getCodePostal()),
            "ville" => FormModel::string("Ville")->required()->default($formData['ville'] ?? $etudiant->getNomVille()),
            "telephone" => FormModel::phone("Téléphone")->default($formData['telephone'] ?? $etudiant->getNumtelutilisateur()),
            "emailPerso" => FormModel::email("Email")->required()->default($formData['emailPerso'] ?? $etudiant->getEmailPerso()),
            "emailUniv" => FormModel::email("Email universitaire")->required()->default($formData['emailUniv'] ?? $etudiant->getEmailutilisateur()),
            "CPAM" => FormModel::string("CPAM et Adresse postal")->required()->default($formData['CPAM'] ?? null),
            "anneeUni" => FormModel::select("Année universitaire", ["2023-2024" => "2023-2024", "2024-2025" => "2024-2025", "2025-2026" => "2025-2026"])->required()->default($formData['anneeUni'] ?? null),
            "nbHeure" => FormModel::int("Nombre d'heure")->required()->default($formData['nbHeure'] ?? null)
        ]);

        if ($request->getMethod() === 'get') {
            return $this->render('simulateurP/simulateuretu', ['form' => $form]);
        } else {
            $_SESSION['form_data'] = $_POST;  // Stocke les données du formulaire dans la session
            Application::$app->response->redirect("/simulateurOffre");
        }
    }


    public function simulateurOffre(Request $request): string
    {
        return $this->render('simulateurP/simulateurOffre');
    }

}