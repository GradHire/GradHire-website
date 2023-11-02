<?php

namespace app\src\controller;

use app\src\model\Form\FormModel;
use app\src\model\ImportPstage;
use app\src\model\Request;


class ImportController extends AbstractController
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
                $importer->importerligne($data);
            }
        }
        return $this->render('Import', [
            'form' => $form
        ]);
    }
}