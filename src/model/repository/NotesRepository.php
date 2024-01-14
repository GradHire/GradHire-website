<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Notes;
use Exception;

class NotesRepository extends AbstractRepository
{
    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public function getByIdnote(int $idnote): ?Notes
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE idnote = ?";
        $data = self::Fetch($sql, [$idnote]);
        return $data ? $this->construireDepuisTableau($data) : null;
    }

    protected function getNomTable(): string
    {
        return "Notes";
    }

    /**
     * @throws Exception
     */
    protected function construireDepuisTableau(array $dataObjectFormatTableau): Notes
    {
        return new Notes($dataObjectFormatTableau);
    }

    /**
     * @throws ServerErrorException
     */
    public function create(array $dataObject): void
    {
        $sql = "SELECT idnote FROM Notes WHERE idnote = (SELECT MAX(idnote) FROM Notes)";
        $data = self::Fetch($sql);
        $id = $data["idnote"] + 1;
        $dataObject["idnote"] = $id;
        $dataObject["valide"] = 0;
        $sql = "INSERT INTO " . $this->getNomTable() . " (" . implode(", ", $this->getNomColonnes()) . ") VALUES ( ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        self::Execute($sql, [
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

    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public function getById(int $getSoutenance): ?Notes
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE idsoutenance = ?";
        $data = self::Fetch($sql, [$getSoutenance]);
        return $data ? $this->construireDepuisTableau($data) : null;
    }

    /**
     * @throws ServerErrorException
     * @throws Exception
     */
    public function getAllnonvalide(): array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE valide = 0";
        $data = self::FetchAll($sql) ?? [];
        $res = [];
        foreach ($data as $key => $value)
            $res[$key] = $this->construireDepuisTableau($value);
        return $res;
    }

    /**
     * @throws ServerErrorException
     */
    public function valideById(int $id): void
    {
        self::Execute("UPDATE " . $this->getNomTable() . " SET valide = 1 WHERE idnote = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public function modifierNote(mixed $idnote, array $notemodif): void
    {
        $sql = "UPDATE " . $this->getNomTable() . " SET noteoral = ?, noterapport = ?, noterelation = ?, notedemarche = ?, noteresultat = ? WHERE idnote = ?";
        self::Execute($sql, [
            $notemodif["noteoral"],
            $notemodif["noterapport"],
            $notemodif["noterelation"],
            $notemodif["notedemarche"],
            $notemodif["noteresultat"],
            $idnote
        ]);
    }
}
