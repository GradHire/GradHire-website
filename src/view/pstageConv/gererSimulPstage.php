<?php

use app\src\core\components\Table;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\SimulationPstageRepository;

$files = new SimulationPstageRepository([]);
if (Auth::has_role(Roles::Student)) {
    $files = $files->getByIdEtudiant(Auth::get_user()->id);
} else $files = $files->getAll();
if ($files == null) {
    echo "Aucune simulation trouvé";
} else {
    if (Auth::has_role(Roles::Student)) {
        echo "Si votre convention est validé vous pouvez passez sur Pstage \n";
    }
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
        if (!Auth::has_role(Roles::Student)) {
            if ($file->getStatut() != "Validee") {
                Table::button("/gererSimulPstage/valide/" . $file->getIdSimulation(), "Valider", "green");
            }
            if ($file->getStatut() != "Refusee") {
                Table::button("/gererSimulPstage/refuse/" . $file->getIdSimulation(), "Refuser", "red");
            }
        }
    });
} ?>
