<?php


namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use Cassandra\Map;

abstract class AbstractRepositoryObject
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

        if ($filter['sujet'] == "" && !empty($filter)) {
            $sql = "SELECT * FROM " . $this->tableChecker($filter);
        } else {
            $sql = "SELECT * FROM " . $this->tableChecker($filter) . " WHERE ";
            //            i need to explode the search for remove ' ' for place each of them into an array
            $arraySearch = explode(' ', $filter['sujet']);
            $sql .= self::prepareSQLQSearch($arraySearch);
        }

        if (array_key_exists('gratificationMin', $filter) && array_key_exists('gratificationMax', $filter)) {
            if ($filter['gratificationMin'] == null && $filter['gratificationMax'] == null) unset($filter['gratificationMin'], $filter['gratificationMax']);
        }


        if (self::checkOnlyStageAletnance($filter)) {
            $pdoStatement = Database::get_conn()->prepare($sql);
            $pdoStatement->execute();

        } else if (self::checkFilterNotEmpty($filter)) {
            if ($filter['sujet'] == "") $sql .= " WHERE ";
            else $sql .= " AND ";

            if (array_key_exists('gratificationMin', $filter) || array_key_exists('gratificationMax', $filter)) {
                //pour chaque valeur de prepareSQLGratification on AJOUTE LA KEY SQL A SQL ET on update les valeur du filter
                foreach (self::prepareSQLGratification(sizeof($filter), $filter) as $key => $value) {
                    if ($key == 'sql') $sql .= $value;
                    else $filter[$key] = $value;
                }
            }

            if (array_key_exists('alternance', $filter)) unset($filter['alternance']);
            if (array_key_exists('stage', $filter)) unset($filter['stage']);

            foreach ($filter as $key => $value) if ($value != "") $values[$key] = explode(',', $value);

            $sql .= self::prepareSQLFilter($values);
            $sql = self::removeEndifAlone($sql);

            $pdoStatement = Database::get_conn()->prepare($sql);

            print_r($values);
            print_r($arraySearch);
            print_r($filter);
            $values = self::constructSQLValues($values, $arraySearch, $filter);
            echo $sql;
            $pdoStatement->execute($values);
        } else {
            $pdoStatement = Database::get_conn()->prepare($sql);
            echo $sql;
            $pdoStatement->execute($arraySearch);
        }

        $dataObjects = [];
        foreach ($pdoStatement as $dataObjectFormatTableau) {
            $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        }
        return $dataObjects;

    }

    //pour le filtre il faut recuperer les case du filtre cocher pour apres les implementers dans la requete
    //pour cela il faut faire une barre de recherche avec des switch case pour chaque filtre possbile (sujet, durée du stage/alternance, localisation, etc)

    /**
     * @throws ServerErrorException
     */
    public function recuperer(): array
    {
        $sql = Database::get_conn()->query("SELECT * FROM " . $this->getNomTable());
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) {
            $dataObjects[] = $this->construireDepuisTableau($dataObjectFormatTableau);
        }
        return $dataObjects;
    }

    protected abstract function getNomTable(): string;

    protected abstract function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject;

    protected abstract function getNomColonnes(): array;

    private static function checkFilterNotEmpty(array $filter): bool
    {
        foreach ($filter as $key => $value) {
            if ($value != "") return true;
        }
        return false;
    }

    public function tableChecker($filter): string
    {
        if (array_key_exists('alternance', $filter) && $filter['alternance'] != "") {
            return "Offrealternance JOIN Offre ON Offrealternance.idoffre = Offre.idoffre";
        } else if (array_key_exists('stage', $filter) && $filter['stage'] != "") {
            return "Offrestage JOIN Offre ON Offrestage.idoffre = Offre.idoffre";
        } else {
            return $this->getNomTable();
        }
    }

    private static function checkOnlyStageAletnance($filter): bool
    {
        //return true if it contains only stage or alternance
        if (array_key_exists('alternance', $filter) && array_key_exists('stage', $filter)) {
            return true;
        } else if (array_key_exists('alternance', $filter)) {
            if (sizeof($filter) == 1) return true;
        } else if (array_key_exists('stage', $filter)) {
            if (sizeof($filter) == 1) return true;
        }
        return false;
    }

    private static function prepareSQLGratification(int $size, array $filter): array
    {
        $sql = array();
        $sql = array_merge($sql, $filter);


        $gratificationMaxTemp = $sql['gratificationMax'];
        $gratificationMinTemp = $sql['gratificationMin'];

        if ($sql['gratificationMin'] == null && $sql['gratificationMax'] == null) {
            return $sql;
        } else if ($sql['gratificationMin'] != null && $sql['gratificationMax'] == null) {
            $gratificationMaxTemp = 15;
        } else if ($sql['gratificationMin'] == null && $sql['gratificationMax'] != null) $gratificationMinTemp = 4.05;

        if ($size > 1) $sql['sql'] = " gratification BETWEEN :gratificationMinTag AND :gratificationMaxTag AND ";
        else $sql['sql'] = " gratification BETWEEN :gratificationMinTag AND :gratificationMaxTag ";

        $sql['gratificationMin'] = $gratificationMinTemp;
        $sql['gratificationMax'] = $gratificationMaxTemp;

        return $sql;
    }

    private static function prepareSQLFilter(array $values): string
    {
        $sql = "";
        foreach ($values as $key => $value) {
            if ($key != 'gratificationMin' && $key != 'gratificationMax' && $key != 'sujet') {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == count($value) - 1) $sql .= $key . " = :" . $key . $key2 . "Tag AND ";
                    else $sql .= $key . " = :" . $key . $key2 . "Tag OR ";
                }
            }
        }
        return $sql;
    }

    private static function prepareSQLQSearch(array $arraySearch): string
    {
        {
            $sql = "";
            foreach ($arraySearch as $key => $value) {
                if ($key == sizeof($arraySearch) - 1) $sql .= "sujet LIKE " . ":sujet" . $key . "Tag ";
                else $sql .= "sujet LIKE " . ":sujet" . $key . "Tag OR ";
            }
            return $sql;
        }
    }

    private static function removeEndifAlone(string $sql): string
    {
        if (substr($sql, -4) == "AND " || substr($sql, -3) == "OR ") $sql = substr($sql, 0, -4);
        return $sql;
    }

    private static function constructSQLValues(?array $values, ?array $arraySearch, ?array $filter): array
    {
        if ($values != null) {
            foreach ($values as $key => $value) {
                if ($key != 'gratificationMin' && $key != 'gratificationMax' && $key != 'sujet') {
                    if ($key == 'thematique') {
                        foreach ($value as $key2 => $value2) {
                            $values[$key . $key2 . "Tag"] = $value2;
                        }
                    } else {
                        $values[$key . "Tag"] = $filter[$key];
                    }
                }
                else {
                    $values['gratificationMinTag'] = $filter['gratificationMin'];
                    $values['gratificationMaxTag'] = $filter['gratificationMax'];
                }
                unset($values[$key]);
            }
        }
        if ($arraySearch != null) {
            foreach ($arraySearch as $key => $value) {
                $arraySearch['sujet' . $key . "Tag"] = '%' . $value . '%';
                unset($arraySearch[$key]);
            }
        }
        if ($values != null && $arraySearch != null) return array_merge($values, $arraySearch);
        else if ($values != null) return $values;
        else if ($arraySearch != null) return $arraySearch;
        else return array();
    }
}
