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
        if (!$this->recordExists('Utilisateur', 'email', $row[7])) $this->insertEtudiant($row);
        else $this->updateEtudiant($row);

        if (!$this->recordExists('Entreprise', 'siret', $row[55])) $this->insertEntreprise($row);
        else $this->updateEntreprise($row);
        $identreprise = $this->find('Entreprise', 'siret', $row[55], 'id');
        if ($this->recordExists('Utilisateur', 'email', $row[79])) $this->insertTuteur($row, $identreprise);
        else $this->updateTuteur($row, $identreprise);

        if ($this->recordExists('Signataire', 'mailSignataire', $row[34])) $this->insertSignataire($row);
        else $this->updateSignataire($row);
        $idsignataire = $this->find('Signataire', 'mailSignataire', $row[34], 'id');
        if ($this->recordExists('Utilisateur', 'email', $row[31])) $this->insertReferent($row);

        $idOffre = $this->insertOffreStage($row);

        $this->insertConvention($row, $idsignataire, $identreprise, $idOffre);
    }

    private function recordExists($table, $field, $value)
    {
        return $this->find($table, $field, $value, $field) ? true : false;
    }

    private function find($table, $field, $value, $selectField)
    {
        $stmt = $this->db->prepare("SELECT $selectField FROM $table WHERE $field = $value");
        $stmt->execute();
        return $stmt->fetch();
    }

    private function execute($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    private function insertEtudiant($row)
    {
        $this->execute("SELECT creerEtuImport ($row[1],$row[2],$row[3], $row[4], $row[6],$row[7], $row[42], $row[44], $row[45], $row[46], $row[47]) FROM dual");
    }

    private function updateEtudiant($row)
    {
        $this->execute("SELECT updateEtuImport ($row[1],$row[2],$row[3], $row[4], $row[6],$row[7], $row[42], $row[44], $row[45], $row[46], $row[47]) FROM dual");
    }

    private function insertEntreprise($row)
    {
        $this->execute("SELECT creerEntImport($row[54],$row[68],$row[66],$row[62],$row[63],$row[64],$row[65],$row[67],$row[69],$row[57],$row[58],$row[56],$row[59],$row[61],$row[60],$row[55]) FROM dual");
    }

    private function updateEntreprise($row)
    {
        $this->execute("SELECT updateEntImport($row[54],$row[68],$row[66],$row[62],$row[63],$row[64],$row[65],$row[67],$row[69],$row[57],$row[58],$row[56],$row[59],$row[61],$row[60],$row[55]) FROM dual");
    }

    private function insertTuteur($row, $identreprise)
    {
        $this->execute("Select creerTuteur($row[78],$row[77],$row[79],$row[81],$identreprise,'',$row[80]) FROM DUAL ;");
    }

    private function updateTuteur($row, mixed $identreprise)
    {
        $this->execute("Select updateTuteurImp($row[77],$row[78],$row[79],$row[80],$row[81]) FROM DUAL ;");
    }

    private function insertSignataire($row)
    {
        $this->execute("Select creerSignataire($row[32],$row[33],$row[34],$row[35]) FROM DUAL ;");
    }

    private function updateSignataire($row)
    {
        $this->execute("Select updateSignataire($row[32],$row[33],$row[34],$row[35]) FROM DUAL ;");
    }

    private function insertReferent($row)
    {
        $this->execute("Select creerStaff($row[29],$row[30],$row[31],$row[31]) FROM DUAL ;");
    }

    private function insertOffreStage($row): int
    {
        $description = $row[20] . $row[21];
        $this->execute("SELECT creerOffreStage($row[22],$row[18],$row[19],$row[23],$row[24],$row[25],$row[43],$row[13],$row[14],'assigner',1,$row[36],$description) FROM DUAL;");
        $sql = "SELECT idOffre FROM Offre WHERE thematique = $row[18] AND sujet = $row[19]";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function insertConvention($row, $idsignataire, $identreprise, $idOffre)
    {
        $this->execute("SELECT creerConvention($row[0],$row[53],$row[28],$row[48],$row[52],$row[51],$idsignataire,$row[15],$row[16],$row[17],$identreprise,$idOffre,$row[38]) FROM DUAL;");
    }

}
