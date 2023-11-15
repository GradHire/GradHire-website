<?php


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
    });
} ?>
