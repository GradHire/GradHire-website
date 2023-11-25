<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Notes;

class NotesRepository extends AbstractRepository
{
    public function create(array $dataObject): void
    {
        $dataObject["idnote"] = null;
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

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Notes
    {
        return new Notes($dataObjectFormatTableau);
    }
}