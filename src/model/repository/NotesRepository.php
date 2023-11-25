<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Notes;

class NotesRepository extends AbstractRepository
{
    public function create(array $dataObject): void
    {
        $sql = "SELECT idnote FROM Notes WHERE idnote = (SELECT MAX(idnote) FROM Notes)";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetch();
        $id = $id["idnote"] + 1;
        $dataObject["idnote"] = $id;
        $sql = "INSERT INTO " . $this->getNomTable() . " (" . implode(", ", $this->getNomColonnes()) . ") VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
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
        ]);
    }

    protected function getNomTable(): string
    {
        return "Notes";
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
        ];
    }

    public function getAll(): ?array
    {
        parent::getAll();
    }

    public function getById(int $getSoutenance): ?Notes
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE idsoutenance = ?";
        $stmt = Database::get_conn()->prepare($sql);
        $stmt->execute([$getSoutenance]);
        $dataObjectFormatTableau = $stmt->fetch();
        return $this->construireDepuisTableau($dataObjectFormatTableau);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Notes
    {
        return new Notes($dataObjectFormatTableau);
    }
}