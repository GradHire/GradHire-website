<?php

namespace app\src\model;

use app\src\core\db\Database;

class ImportPstage
{
    public function importerligne($row){
        if(!empty($row[8]) && !$this->exist($row[8],"Uniteformationrecherche","codeufr")) {
            $this->insertIntoUniteformationrecherche($row[8],$row[9]);
        }
        $this->insertIntoDepartement($row[8],$row[10]);
        $this->insertIntoEtape($row[11],$row[12],$row[10]);
        $this->insertIntoEtudiant($row[1],$row[2],$row[3],$row[4],$row[6],$row[7],$row[43],$row[45],$row[46],$row[47],$row[48]);
    }
    private function insertIntoEtudiant($numetudiant, $nom, $prenom, $tel, $mailperso, $mailetu, $sexe, $adresse, $codepostal, $pays, $ville){
        $sql= "SELECT creerEtu FROM Dual";
    }

    protected function insertIntoUniteformationrecherche($codeufr, $libelleufr){
        $sql = "INSERT INTO Uniteformationrecherche (codeufr,libelleufr) VALUES (?, ?)";
        $this->executeQuery($sql, [$codeufr, $libelleufr]);
    }

    protected function executeQuery($sql, array $params = []){
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute($params);
    }

    protected function insertIntoDepartement($codeufr = null, $codedepartement = null){
        $codedepartement = $codedepartement ?? $codeufr;
        $codeufr = $codeufr ?? $codedepartement;

        if(empty($codedepartement) || empty($codeufr) || $this->exist($codedepartement, "Departement", "codedepartement")) return;

        $sql = "INSERT INTO Departement VALUES (?, ?)";
        $this->executeQuery($sql, [$codedepartement, $codeufr]);
    }

    public function exist($data, $table, $colonne): bool{
        $sql = "SELECT $colonne FROM $table WHERE $colonne = ?";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([$data]);
        return $stmt->fetchColumn() !== false;
    }

    private function insertIntoEtape($codeetape, $libelleetape, $codedepartement){
        if(empty($codeetape) || $this->exist($codeetape, "Etape", "codeetape")) return;

        $sql = "INSERT INTO Etape VALUES (?, ?, ?)";
        $this->executeQuery($sql, [$codeetape, $libelleetape, $codedepartement]);
    }


}
