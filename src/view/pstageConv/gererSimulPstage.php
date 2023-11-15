<?php

use app\src\core\components\Table;
use app\src\model\Form\FormModel;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\SimulationPstageRepository;

$files = new SimulationPstageRepository([]);
$files = $files->getAll();

if ($files == null) {
    echo "Aucune simulation trouvé";
} else {
    Table::createTable($files, ["nom", "prénom", "numEtudiant", "convention", "statut"], function ($file) {
        $etudiant = new EtudiantRepository([]);
        $etudiant = $etudiant->getByIdFull($file->getIdEtudiant());
        Table::cell($etudiant->getNomutilisateur());
        Table::cell($etudiant->getPrenom());
        Table::cell($etudiant->getNumEtudiant());
        Table::pdfLink("uploads/Pstage/" . $file->getNomFichier(), $file->getNomFichier());
        if ($file->getStatut() == "En attente") {
            Table::chip("en Attente", "yellow");
        } else if ($file->getStatut() == "Validee") {
            Table::chip("Validé", "green");
        } else if ($file->getStatut() == "Refusee") {
            Table::chip("Refusé", "red");
        }
        Table::button("/gererSimulPstage/valide/" . $file->getIdSimulation(), "valide");
        Table::button("/gererSimulPstage/refuse/" . $file->getIdSimulation(), "refuse");
    });

} ?>
