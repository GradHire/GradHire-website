<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Notes;

class NotesRepository extends AbstractRepository
{
    public function getByIdnote(int $idnote): ?Notes
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE idnote = ?";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([$idnote]);
        $dataObjectFormatTableau = $stmt->fetch();
        if ($dataObjectFormatTableau === false) {
            return null;
        }
        return $this->construireDepuisTableau($dataObjectFormatTableau);
    }

    protected function getNomTable(): string
    {
        return "Notes";
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Notes
    {
        return new Notes($dataObjectFormatTableau);
    }

    public function create(array $dataObject): void
    {
        $sql = "SELECT idnote FROM Notes WHERE idnote = (SELECT MAX(idnote) FROM Notes)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetch();
        $id = $id["idnote"] + 1;
        $dataObject["idnote"] = $id;
        $dataObject["valide"] = 0;
        $sql = "INSERT INTO " . $this->getNomTable() . " (" . implode(", ", $this->getNomColonnes()) . ") VALUES ( ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        $stmt = Database::get_conn()->prepare($sql);

        $stmt->execute([
            $dataObject["idnote"],
            $dataObject["etudiant"],
            $dataObject["presenttuteur"],
            $dataObject["renduretard"],
            $dataObject["noterapport"],
            $dataObject["commentairerapport"],
            $dataObject["noteoral"],
            $dataObject["commentaireoral"],
            $dataObject["noterelation"],
            $dataObject["langage"],
            $dataObject["nouveau"],
            $dataObject["difficulte"],
            $dataObject["notedemarche"],
            $dataObject["noteresultat"],
            $dataObject["commentaireresultat"],
            $dataObject["recherche"],
            $dataObject["recontact"],
            $dataObject["idsoutenance"],
            $dataObject["valide"]
        ]);
    }

    protected function getNomColonnes(): array
    {
        return [
            "idnote",
            "etudiant",
            "presenttuteur",
            "renduretard",
            "noterapport",
            "commentairerapport",
            "noteoral",
            "commentaireoral",
            "noterelation",
            "langage",
            "nouveau",
            "difficulte",
            "notedemarche",
            "noteresultat",
            "commentaireresultat",
            "recherche",
            "recontact",
            "idsoutenance",
            "valide"
        ];
    }

    public function getAll(): ?array
    {
        return parent::getAll();
    }

    public function getById(int $getSoutenance): ?Notes
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE idsoutenance = ?";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([$getSoutenance]);
        $dataObjectFormatTableau = $stmt->fetch();
        if ($dataObjectFormatTableau === false) {
            return null;
        }
        return $this->construireDepuisTableau($dataObjectFormatTableau);
    }

    public function getAllnonvalide()
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE valide = 0";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute();
        $dataObjectFormatTableau = $stmt->fetchAll();
        if ($dataObjectFormatTableau === false) {
            return null;
        }
        foreach ($dataObjectFormatTableau as $key => $value) {
            $dataObjectFormatTableau[$key] = $this->construireDepuisTableau($value);
        }
        return $dataObjectFormatTableau;
    }

    public function valideById(int $id)
    {
        $sql = "UPDATE " . $this->getNomTable() . " SET valide = 1 WHERE idnote = ?";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([$id]);
    }

    public function modifierNote(mixed $idnote, array $notemodif)
    {
        $sql = "UPDATE " . $this->getNomTable() . " SET noteoral = ?, noterapport = ?, noterelation = ?, notedemarche = ?, noteresultat = ? WHERE idnote = ?";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([
            $notemodif["noteoral"],
            $notemodif["noterapport"],
            $notemodif["noterelation"],
            $notemodif["notedemarche"],
            $notemodif["noteresultat"],
            $idnote
        ]);
    }
}
