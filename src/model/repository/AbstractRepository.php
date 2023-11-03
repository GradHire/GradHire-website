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
    public function search(array $filter): array
    {
        $pdoStatement = null;
        $values = array();
        $arraySearch = array();
        $sql = "";
        $dataObjects = array();

        if ($filter['sujet'] == "" && !empty($filter)) {
            $sql = "SELECT * FROM " . (new OffresRepository())->tableChecker($filter);
        } else {
            $sql = "SELECT * FROM " . (new OffresRepository())->tableChecker($filter) . " WHERE ";
            //            i need to explode the search for remove ' ' for place each of them into an array
            $arraySearch = explode(' ', $filter['sujet']);
            $sql .= (new OffresRepository())->prepareSQLQSearch($arraySearch);
        }

        if (array_key_exists('gratificationMin', $filter) && array_key_exists('gratificationMax', $filter)) {
            if ($filter['gratificationMin'] == null && $filter['gratificationMax'] == null) unset($filter['gratificationMin'], $filter['gratificationMax']);
        }


        if ((new OffresRepository())->checkOnlyStageOrAlternance($filter)) {
            $pdoStatement = Database::get_conn()->prepare($sql);
            $pdoStatement->execute();


        } else if ((new OffresRepository())->checkFilterNotEmpty($filter)) {
            if ($filter['sujet'] == "") $sql .= " WHERE ";
            else $sql .= " AND ";

            if (array_key_exists('gratificationMin', $filter) || array_key_exists('gratificationMax', $filter)) {
                //pour chaque valeur de prepareSQLGratification on AJOUTE LA KEY SQL A SQL ET on update les valeur du filter
                foreach ((new OffresRepository())->prepareSQLGratification(sizeof($filter), $filter) as $key => $value) {
                    if ($key == 'sql') $sql .= $value;
                    else $filter[$key] = $value;
                }
            }

            if (array_key_exists('alternance', $filter)) unset($filter['alternance']);
            if (array_key_exists('stage', $filter)) unset($filter['stage']);

            foreach ($filter as $key => $value) if ($value != "") $values[$key] = explode(',', $value);

            $sql .= (new OffresRepository())->prepareSQLFilter($values);
            $sql = (new OffresRepository())->removeEndifAlone($sql);
            $pdoStatement = Database::get_conn()->prepare($sql);
            $values = (new OffresRepository())->constructSQLValues($values, $arraySearch, $filter);
            $pdoStatement->execute($values);
        } else {
            $pdoStatement = Database::get_conn()->prepare($sql);
            $pdoStatement->execute($arraySearch);
        }

        foreach ($pdoStatement as $dataObjectFormatTableau) {
            $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        }
        return $dataObjects;
    }

    protected abstract function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject;

    public function getAll(): ?array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . $this->getNomTable());
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    protected abstract function getNomTable(): string;

    protected abstract function getNomColonnes(): array;
}
