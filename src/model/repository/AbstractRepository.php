<?php


namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;

abstract class AbstractRepository
{

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . $this->getNomTable());
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    protected abstract function getNomTable(): string;

    protected abstract function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject;

    protected abstract function getNomColonnes(): array;
}
