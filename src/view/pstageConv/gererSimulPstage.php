<?php
//pour chaque fichier etant dans le path /uploads/Pstage
//on recupere le nom du fichier et on le met dans un tableau

use app\src\model\repository\EtudiantRepository;

$dir = "uploads/Pstage";
$files = scandir($dir);
$files = array_diff(scandir($dir), array('.', '..'));
foreach ($files as $file) {
    $numEtudiant = substr($file, 17, 8);
    $etudiant = new EtudiantRepository([]);
    $etudiant = $etudiant->getByNumEtudiantFull($numEtudiant);

}
