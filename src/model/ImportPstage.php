<?php

namespace app\src\model;

use app\src\core\db\Database;

class ImportPstage
{
    private $db;

    public function __construct()
    {
        $this->db = Database::get_conn();
    }

    public function importerligne($row)
    {
        if (!$this->recordExists('etudiant', 'numetudiant', $row[1])) $this->insertEtudiant($row);
        if (!$this->recordExists('Annee', 'idannee', $row[36])) $this->insertAnnee($row);
        if (!$this->recordExists('Entreprise', 'siret', $row[55])) $this->insertEntreprise($row);
        else {
            $entreprise = $this->find('Entreprise', 'siret', $row[55], 'identreprise');
            $this->insertOffre($row, $entreprise);
        }
    }

    private function find($table, $field, $value, $selectField)
    {
        $stmt = $this->db->prepare("SELECT $selectField FROM $table WHERE $field = $value");
        $stmt->execute();
        return $stmt->fetch();
    }

    private function recordExists($table, $field, $value)
    {
        return $this->find($table, $field, $value, $field) ? true : false;
    }

    private function insertEtudiant($row)
    {
        $this->execute("SELECT creerEtu2 ($row[1],$row[2],$row[3], $row[4], $row[6],$row[7], $row[43], $row[45], $row[46], $row[47], $row[48]) FROM dual");
    }

    private function insertAnnee($row)
    {
        $this->execute("INSERT INTO Annee ($row[36])");
    }

    private function insertOffre($row, $entreprise)
    {
        $this->execute("INSERT INTO Offre (null,row[22],row[18],row[19],$row[23],row[24],row[25],row[26],row[43],row[13],row[14],null,row[36],$entreprise,row[20],null,'attribue')");
    }

    private function execute($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    private function insertEntreprise($row)
    {
        
    }
}
