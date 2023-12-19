<?php

namespace app\src\model;

use app\src\core\db\Database;

class Import
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
        $idetu = $this->find('Utilisateur', 'email', $row[7], 'idUtilisateur');

        if (!$this->recordExists('Entreprise', 'siret', $row[55])) $this->insertEntreprise($row);
        else $this->updateEntreprise($row);
        $identreprise = $this->find('Entreprise', 'siret', $row[55], 'idUtilisateur');

        if (!$this->recordExists('Utilisateur', 'email', $row[79])) $this->insertTuteur($row, $identreprise);
        else $this->updateTuteur($row, $identreprise);
        $idtuteur = $this->find('Utilisateur', 'email', $row[79], 'idUtilisateur');

        if (!$this->recordExists('Signataire', 'mailSignataire', $row[34])) $this->insertSignataire($row, $identreprise);
        else $this->updateSignataire($row);
        $idsignataire = $this->find('Signataire', 'mailSignataire', $row[34], 'idSignataire');
        if (!$this->recordExists('Utilisateur', 'email', $row[31])) $this->insertReferent($row);
        $idreferent = $this->find('Utilisateur', 'email', $row[31], 'idUtilisateur');

        if (!$this->exist('ServiceAccueil', 'nomService', $row[70], 'idEntreprise', $identreprise)) $this->insertServiceAccueil($row, $identreprise);
        else $this->updateServiceAccueil($row, $identreprise);

        $idOffre = $this->insertOffreStage($row, $identreprise);

        if (!$this->recordExists('Postuler', 'idOffre', $idOffre) || !$this->recordExists('Postuler', 'idutilisateur', $idetu)) {
            $this->insertPostuler($row, $idOffre, $idetu);
            $this->insertSuperviser($row, $idOffre, $idetu, $idreferent, $idtuteur);
        }

        if (!$this->recordExists('Convention', 'numConvention', $row[0])) $this->insertConvention($row, $idsignataire, $idetu, $idOffre);

    }

    private function recordExists($table, $field, $value)
    {
        return $this->find($table, $field, $value, $field) ? true : false;
    }

    private function find($table, $field, $value, $selectField)
    {
        $stmt = $this->db->prepare("SELECT $selectField FROM $table WHERE $field = '$value'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    private function insertEtudiant($row)
    {
        $sql = "SELECT creerEtuImp (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
        $this->extracted($sql, $row);
    }

    /**
     * @param string $sql
     * @param $row
     * @return void
     */
    private function extracted(string $sql, $row): void
    {
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[1]);
        $stmt->bindParam(2, $row[2]);
        $stmt->bindParam(3, $row[3]);
        $stmt->bindParam(4, $row[4]);
        $stmt->bindParam(5, $row[6]);
        $stmt->bindParam(6, $row[7]);
        $stmt->bindParam(7, $row[42]);
        $stmt->bindParam(8, $row[44]);
        $stmt->bindParam(9, $row[45]);
        $stmt->bindParam(10, $row[46]);
        $stmt->bindParam(11, $row[47]);
        $stmt->bindParam(12, $row[12]);
        $stmt->execute();
    }

    private function updateEtudiant($row)
    {
        $sql = "Call updateEtuImp(?,?,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)";
        $this->extracted($sql, $row);
    }

    private function insertEntreprise($row)
    {
        $sql = "SELECT creerEntImp(?,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?) ";
        $this->extracted1($sql, $row);
    }

    /**
     * @param string $sql
     * @param $row
     * @return void
     */
    private function extracted1(string $sql, $row): void
    {
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[54]);
        $stmt->bindParam(2, $row[68]);
        $stmt->bindParam(3, $row[66]);
        $stmt->bindParam(4, $row[62]);
        $stmt->bindParam(5, $row[63]);
        $stmt->bindParam(6, $row[64]);
        $stmt->bindParam(7, $row[65]);
        $stmt->bindParam(8, $row[67]);
        $stmt->bindParam(9, $row[69]);
        $stmt->bindParam(10, $row[57]);
        $stmt->bindParam(11, $row[58]);
        $stmt->bindParam(12, $row[56]);
        $stmt->bindParam(13, $row[59]);
        $stmt->bindParam(14, $row[61]);
        $stmt->bindParam(15, $row[60]);
        $stmt->bindParam(16, $row[55]);
        $stmt->execute();
    }

    private function updateEntreprise($row)
    {

        $sql = "Call updateEntImp(?,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)";
        $this->extracted1($sql, $row);
    }

    private function insertTuteur($row, $identreprise)
    {
        $null = 'null';
        $sql = "Select creerTuteur(?,? ,? ,? ,? ,? ,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[78]);
        $stmt->bindParam(2, $row[77]);
        $stmt->bindParam(3, $row[79]);
        $stmt->bindParam(4, $row[81]);
        $stmt->bindParam(5, $identreprise);
        $stmt->bindParam(6, $null);
        $stmt->bindParam(7, $row[80]);
        $stmt->execute();
    }

    private function updateTuteur($row, $identreprise)
    {
        $sql = "Call updateTuteurImp(?,? ,? ,? ,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[77]);
        $stmt->bindParam(2, $row[78]);
        $stmt->bindParam(3, $row[79]);
        $stmt->bindParam(4, $row[80]);
        $stmt->bindParam(5, $row[81]);
        $stmt->execute();
    }

    private function insertSignataire($row, $identreprise)
    {
        $sql = "Select creerSignataire(?,? ,? ,? ,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[32]);
        $stmt->bindParam(2, $row[33]);
        $stmt->bindParam(3, $row[34]);
        $stmt->bindParam(4, $row[35]);
        $stmt->bindParam(5, $identreprise);
        $stmt->execute();
    }

    private function updateSignataire($row)
    {
        $sql = "Call updateSignataire(?,? ,? ,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[32]);
        $stmt->bindParam(2, $row[33]);
        $stmt->bindParam(3, $row[34]);
        $stmt->bindParam(4, $row[35]);
        $stmt->execute();
    }

    private function insertReferent($row)
    {
        $sql = "Select creerStaffImp(?,? ,? ,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[29]);
        $stmt->bindParam(2, $row[30]);
        $stmt->bindParam(3, $row[31]);
        $stmt->bindParam(4, $row[31]);
        $stmt->execute();
    }

    private function exist(string $string, string $string1, mixed $int, string $string2, mixed $identreprise)
    {
        $stmt = $this->db->prepare("SELECT * FROM $string WHERE $string1 = '$int' AND $string2 = '$identreprise'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function insertServiceAccueil($row, mixed $identreprise)
    {
        $sql = "CALL creerServiceAccueil(?,? ,? ,? ,? ,? ,? ,?) ";
        $identreprise = $this->getIdentreprise($sql, $row, $identreprise);
    }

    /**
     * @param string $sql
     * @param $row
     * @param mixed $identreprise
     * @return mixed
     */
    private function getIdentreprise(string $sql, $row, mixed $identreprise): mixed
    {
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[70]);
        $stmt->bindParam(2, $row[71]);
        $stmt->bindParam(3, $row[72]);
        $stmt->bindParam(4, $row[73]);
        $stmt->bindParam(5, $row[74]);
        $stmt->bindParam(6, $row[75]);
        $stmt->bindParam(7, $row[76]);
        $stmt->bindParam(8, $identreprise);
        $stmt->execute();
        return $identreprise;
    }

    private function updateServiceAccueil($row, mixed $identreprise)
    {
        $sql = "Call updateServiceAccueil(?,? ,? ,? ,? ,? ,? ,?)";
        $identreprise = $this->getIdentreprise($sql, $row, $identreprise);
    }

    private function insertOffreStage($row, $idEntreprise): int
    {
        $description = $row[20] . $row[21];
        $valide = "valider";
        $valider = 1;
        $sql = "SELECT creerOffreStage(?,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[22]);
        $stmt->bindParam(2, $row[18]);
        $stmt->bindParam(3, $row[19]);
        $stmt->bindParam(4, $row[23]);
        $stmt->bindParam(5, $row[24]);
        $stmt->bindParam(6, $row[25]);
        $stmt->bindParam(7, $row[43]);
        $stmt->bindParam(8, $row[13]);
        $stmt->bindParam(9, $row[14]);
        $stmt->bindParam(10, $valide);
        $stmt->bindParam(11, $valider);
        $stmt->bindParam(12, $row[36]);
        $stmt->bindParam(13, $idEntreprise);
        $stmt->bindParam(14, $description);
        $stmt->execute();

        $sql = "SELECT idOffre FROM Offre WHERE thematique = '$row[18]' AND sujet = '$row[19]'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function insertPostuler($row, int $idOffre, mixed $idetu)
    {
        $sql = "SELECT creerPostuler(?,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $idetu);
        $stmt->bindParam(2, $idOffre);
        $stmt->execute();
    }

    private function insertSuperviser($row, int $idOffre, mixed $idetu, mixed $idreferent, mixed $idtuteur)
    {
        $valide = "validee";
        $sql = "INSERT INTO Supervise VALUES(?,? ,? ,? ,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $idreferent);
        $stmt->bindParam(2, $idOffre);
        $stmt->bindParam(3, $idetu);
        $stmt->bindParam(4, $valide);
        $stmt->bindParam(5, $idtuteur);
        $stmt->execute();
    }

    private function insertConvention($row, $idsignataire, $idetu, $idOffre)
    {
        $sql = "SELECT creerConvention(?,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?) ";
        $row[28] = ($row[28] == "Oui") ? 1 : 0;
        $row[48] = ($row[48] == "Oui") ? 1 : 0;
        $row[51] = date('Y-m-d H:i:s', strtotime($row[51]));
        $row[52] = date('Y-m-d H:i:s', strtotime($row[52]));
        $row[16] = date('Y-m-d', strtotime($row[16]));
        $row[17] = date('Y-m-d', strtotime($row[17]));
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $row[0]);
        $stmt->bindParam(2, $row[53]);
        $stmt->bindParam(3, $row[28]);
        $stmt->bindParam(4, $row[48]);
        $stmt->bindParam(5, $row[52]);
        $stmt->bindParam(6, $row[51]);
        $stmt->bindParam(7, $idsignataire);
        $stmt->bindParam(8, $row[15]);
        $stmt->bindParam(9, $row[16]);
        $stmt->bindParam(10, $row[17]);
        $stmt->bindParam(11, $idetu);
        $stmt->bindParam(12, $idOffre);
        $stmt->bindParam(13, $row[38]);
        $stmt->execute();
    }

    public function importerligneStudea($data)
    {

        if (!$this->recordExists('Utilisateur', 'email', $data[28])) $this->insertEtudiantStudea($data);
        else $this->updateEtudiantStudea($data);
        $idetu = $this->find('Utilisateur', 'email', $data[28], 'idUtilisateur');

        if (!$this->recordExists('Entreprise', 'siret', $data[58])) $this->insertEntrepriseStudea($data);
        else $this->updateEntrepriseStudea($data);
        $identreprise = $this->find('Entreprise', 'siret', $data[58], 'idUtilisateur');

        if (!$this->recordExists('Utilisateur', 'email', $data[111])) $this->insertTuteurStudea($data, $identreprise);
        else $this->updateTuteurStudea($data);

        $idtuteur = $this->find('Utilisateur', 'email', $data[111], 'idUtilisateur');

        if (!$this->recordExists('Signataire', 'mailSignataire', $data[96])) $this->insertSignataireStudea($data, $identreprise);
        else $this->updateSignataireStudea($data);
        $idsignataire = $this->find('Signataire', 'mailSignataire', $data[96], 'idSignataire');

        $idreferent = 90;
        $idOffre = $this->insertOffreStudea($data, $identreprise);
        $this->updateoffrePourStudea($data, $idOffre);

        if (!$this->recordExists('Postuler', 'idOffre', $idOffre) || !$this->recordExists('Postuler', 'idutilisateur', $idetu)) {
            $this->insertPostuler($data, $idOffre, $idetu);
            $this->insertSuperviser($data, $idOffre, $idetu, $idreferent, $idtuteur);
        }
        if (!$this->recordExists('Convention', 'numConvention', $data[3])) $this->insertConventionStudea($data, $idsignataire, $idetu, $idOffre);
    }

    private function insertEtudiantStudea($data): void
    {
        $sql = "SELECT creerEtuImp(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?) ";
        $this->executeEtuImpQuery($data, $sql);
    }

    private function executeEtuImpQuery($data, $sql): void
    {
        $pays = "France";
        $adresse = $data[30] . " " . $data[31];

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[41]);
        $stmt->bindParam(2, $data[9]);
        $stmt->bindParam(3, $data[10]);
        $stmt->bindParam(4, $data[27]);
        $stmt->bindParam(5, $data[29]);
        $stmt->bindParam(6, $data[28]);
        $stmt->bindParam(7, $data[8]);
        $stmt->bindParam(8, $adresse);
        $stmt->bindParam(9, $data[32]);
        $stmt->bindParam(10, $pays);
        $stmt->bindParam(11, $data[33]);
        $stmt->bindParam(12, $data[5]);
        $stmt->execute();
    }

    private function updateEtudiantStudea($data)
    {
        $sql = "CALL updateEtuImp(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->executeEtuImpQuery($data, $sql);
    }

    private function insertEntrepriseStudea($data)
    {

        $sql = "SELECT creerEntImp(?, ?,?, ?,?,?,?, ?,?, ?,?,? ,?, ?,?,?) ";
        $this->executeEntImpQuery($data, $sql);
    }

    private function executeEntImpQuery($data, $sql)
    {
        $null = "";
        $pays = "France";
        $adresse1 = $data[64] . " " . $data[65];
        $adresse2 = $data[68] . " " . $data[69];

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[60]);
        $stmt->bindParam(2, $null);
        $stmt->bindParam(3, $null);
        $stmt->bindParam(4, $null);
        $stmt->bindParam(5, $data[59]);
        $stmt->bindParam(6, $data[63]);
        $stmt->bindParam(7, $data[61]);
        $stmt->bindParam(8, $null);
        $stmt->bindParam(9, $null);
        $stmt->bindParam(10, $adresse1);
        $stmt->bindParam(11, $adresse2);
        $stmt->bindParam(12, $null);
        $stmt->bindParam(13, $data[66]);
        $stmt->bindParam(14, $pays);
        $stmt->bindParam(15, $data[67]);
        $stmt->bindParam(16, $data[58]);
        $stmt->execute();
    }

    private function updateEntrepriseStudea($data)
    {
        $sql = "CALL updateEntImp(?, ?,?, ?,?,?,?, ?,?, ?,?,? ,?, ?,?,?)";

        $this->executeEntImpQuery($data, $sql);

    }

    private function insertTuteurStudea($data, mixed $identreprise)
    {
        $null = "";
        $sql = "SELECT creerTuteur(?, ?,?, ?,?,?,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[108]);
        $stmt->bindParam(2, $data[107]);
        $stmt->bindParam(3, $data[111]);
        $stmt->bindParam(4, $data[109]);
        $stmt->bindParam(5, $identreprise);
        $stmt->bindParam(6, $null);
        $stmt->bindParam(7, $data[110]);
        $stmt->execute();
    }

    private function updateTuteurStudea($data)
    {
        $sql = "CALL updateTuteurImp(?, ?,?, ?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[107]);
        $stmt->bindParam(2, $data[108]);
        $stmt->bindParam(3, $data[111]);
        $stmt->bindParam(4, $data[110]);
        $stmt->bindParam(5, $data[109]);
        $stmt->execute();
    }

    private function insertSignataireStudea($data, mixed $identreprise)
    {
        $sql = "SELECT creerSignataire(?, ?,?, ?,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[93]);
        $stmt->bindParam(2, $data[94]);
        $stmt->bindParam(3, $data[96]);
        $stmt->bindParam(4, $data[95]);
        $stmt->bindParam(5, $identreprise);
        $stmt->execute();
    }

    private function updateSignataireStudea($data)
    {
        $sql = "CALL updateSignataire(?, ?,?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[93]);
        $stmt->bindParam(2, $data[94]);
        $stmt->bindParam(3, $data[96]);
        $stmt->bindParam(4, $data[95]);
        $stmt->execute();
    }

    private function insertOffreStudea($data, mixed $identreprise)
    {
        $null = "";
        $valider = 1;
        $zero = 0;
        $valide = "valider";
        $anneeuni = $data[6] . "-" . $data[7];
        // Convert the dates to the correct format
        $date139 = $data[139];
        $date140 = $data[140];
        $date139 = date("Y-m-d", strtotime($date139));
        $date140 = date("Y-m-d", strtotime($date140));

        $sql = "SELECT creerOffreAlternance(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[126]);
        $stmt->bindParam(2, $null);
        $stmt->bindParam(3, $null);
        $stmt->bindParam(4, $zero);
        $stmt->bindParam(5, $zero);
        $stmt->bindParam(6, $zero);
        $stmt->bindParam(7, $null);
        $stmt->bindParam(8, $date139);
        $stmt->bindParam(9, $date140);
        $stmt->bindParam(10, $valide);
        $stmt->bindParam(11, $valider);
        $stmt->bindParam(12, $anneeuni);
        $stmt->bindParam(13, $identreprise);
        $stmt->bindParam(14, $data[100]);
        $stmt->execute();

        $sql = "SELECT idOffre FROM Offre WHERE idOffre = (SELECT MAX(idOffre) FROM Offre)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $last_insert = $stmt->fetchColumn();

        return $last_insert;
    }


    private function updateoffrePourStudea($data, mixed $idOffre)
    {
        $sql = "UPDATE Offre SET sujet = 'Offre N°$idOffre' WHERE idOffre = '$idOffre'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    private function insertConventionStudea($data, mixed $idsignataire, mixed $identreprise, mixed $idOffre)
    {
        $null = "";
        $studea = "Studea";
        $date = date("Y-m-d");
        $zero = 0;
        $data[104] = date("Y-m-d", strtotime($data[104]));
        $data[139] = date("Y-m-d", strtotime($data[139]));
        $data[140] = date("Y-m-d", strtotime($data[140]));
        $sql = "SELECT creerConvention(?, ?,?, ?,?,?,?, ?,?, ?,?,? ,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[3]);
        $stmt->bindParam(2, $studea);
        $stmt->bindParam(3, $zero);
        $stmt->bindParam(4, $zero);
        $stmt->bindParam(5, $date);
        $stmt->bindParam(6, $data[104]);
        $stmt->bindParam(7, $idsignataire);
        $stmt->bindParam(8, $null);
        $stmt->bindParam(9, $data[139]);
        $stmt->bindParam(10, $data[140]);
        $stmt->bindParam(11, $identreprise);
        $stmt->bindParam(12, $idOffre);
        $stmt->bindParam(13, $null);
        $stmt->execute();
    }

    public function importerligneScodoc(bool|array $data)
    {
        $isRecordExists = $this->recordExists('Etudiant', 'numetudiant', $data[2]);
        $sql = $isRecordExists ? "CALL updateEtuScodoc(?,?,?,?,?) " : "CALL creerEtuScodoc(?,?,?,?,?) ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $data[0]);
        $stmt->bindParam(2, $data[1]);
        $stmt->bindParam(3, $data[2]);
        $stmt->bindParam(4, $data[3]);
        $str = $data[4] . " " . $data[5];
        $stmt->bindParam(5, $str);
        $stmt->execute();
    }
}
