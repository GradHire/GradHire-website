<?php


namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;

abstract class AbstractRepositoryObject
{
    /**
     * @throws ServerErrorException
     */
    public function search(string $search, array $filter): array
    {
        $pdoStatement = null;
        $values = array();
        $arraySearch = array();
        $sql = "";
        print_r($filter);
        //requete prepare stp
        echo 'search : ' . $search . '<br>';
        if ($search == "" && !empty($filter)) {
            $sql = "SELECT * FROM " . $this->tableChecker($filter);
        } else {
            $sql = "SELECT * FROM " . $this->tableChecker($filter) . " WHERE ";
            //            i need to explode the search for remove ' ' for place each of them into an array
            $arraySearch = explode(' ', $search);
            {
                foreach ($arraySearch as $key => $value) {
                    if ($key == sizeof($arraySearch) - 1) $sql .= "sujet LIKE " . ":sujet" . $key . "Tag ";
                    else $sql .= "sujet LIKE " . ":sujet" . $key . "Tag OR ";
                }
            }
            echo 'arraySearch : ';
            print_r($arraySearch);
            echo '<br>';
        }
        if (self::checkOnlyStageAletnance($filter)) {
            echo 'only stage or alternance';
            $pdoStatement = Database::get_conn()->prepare($sql);
            $pdoStatement->execute();
        } else if (self::checkFilterNotEmpty($filter)) {
            if ($search == "") $sql .= " WHERE ";
            else $sql .= " AND ";
            if (array_key_exists('gratificationMin', $filter) || array_key_exists('gratificationMax', $filter)) {
                if (sizeof($filter) == 1) $sql .= self::prepareSQLGratification($filter['gratificationMin'], $filter['gratificationMax']);
                else if (sizeof($filter) > 1) {
                    $sql .= self::prepareSQLGratification($filter['gratificationMin'], $filter['gratificationMax']) . " AND ";
                }
            }
            if (array_key_exists('alternance', $filter)) unset($filter['alternance']);
            if (array_key_exists('stage', $filter)) unset($filter['stage']);
            foreach ($filter as $key => $value) if ($value != "") $values[$key] = explode(',', $value);
            foreach ($values as $key => $value) {
                if ($key != 'gratificationMin' && $key != 'gratificationMax') {
                    foreach ($value as $key2 => $value2) {
                        if ($key2 == count($value) - 1) $sql .= $key . " = :" . $key . $key2 . "Tag AND ";
                        else $sql .= $key . " = :" . $key . $key2 . "Tag OR ";
                    }
                }
            }
            $sql = substr($sql, 0, -4);
            $pdoStatement = Database::get_conn()->prepare($sql);
            foreach ($values as $key => $value) {
                if ($key != 'gratificationMin' && $key != 'gratificationMax') {
                    foreach ($value as $key2 => $value2) {
                        $values[$key . $key2 . "Tag"] = $value2;
                    }
                } else {
                    self::rangeValueGratification($filter['gratificationMin'], $filter['gratificationMax']);
                    $values['gratificationMinTag'] = $filter['gratificationMin'];
                    $values['gratificationMaxTag'] = $filter['gratificationMax'];
                }
                unset($values[$key]);
            }
            foreach ($arraySearch as $key => $value) {
                $arraySearch['sujet' . $key . "Tag"] = '%' . $value . '%';
                unset($arraySearch[$key]);
                echo 'sujet' . $key . "Tag" . ' : ' . $value . '<br>';
            }
            print_r($pdoStatement);
            print_r($values);
            $values = array_merge($values, $arraySearch);
            $pdoStatement->execute($values);
        } else {
            $pdoStatement = Database::get_conn()->prepare($sql);
            foreach ($arraySearch as $key => $value) {
                $arraySearch['sujet' . $key . "Tag"] = '%' . $value . '%';
                unset($arraySearch[$key]);
                echo 'sujet' . $key . "Tag" . ' : ' . $value . '<br>';
            }
            print_r($pdoStatement);
            echo '<br>';
            print_r($arraySearch);
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

    private static function prepareSQLGratification(): string
    {
//        if ($gratificationMin == null) {
//            $gratificationMin = 4.05;
//        }
//        if ($gratificationMax == null) {
//            $gratificationMax = 15;
//        }
//        //si la gratification min est plus grande que la max alors on inverse les deux
//        if ($gratificationMin > $gratificationMax) {
//            $temp = $gratificationMin;
//            $gratificationMin = $gratificationMax;
//            $gratificationMax = $temp;
//        }
//        if ($gratificationMax < $gratificationMin) {
//            $temp = $gratificationMin;
//            $gratificationMin = $gratificationMax;
//            $gratificationMax = $temp;
//        }
        return " gratification BETWEEN :gratificationMinTag AND :gratificationMaxTag ";
    }

    private static function rangeValueGratification(?float $gratificationMin, ?float $gratificationMax): void
    {
        if ($gratificationMin == null) {
            $gratificationMin = 4.05;
        }
        if ($gratificationMax == null) {
            $gratificationMax = 15;
        }
        //si la gratification min est plus grande que la max alors on inverse les deux
        if ($gratificationMin > $gratificationMax) {
            $temp = $gratificationMin;
            $gratificationMin = $gratificationMax;
            $gratificationMax = $temp;
        }
        if ($gratificationMax < $gratificationMin) {
            $temp = $gratificationMax;
            $gratificationMax = $gratificationMin;
            $gratificationMin = $temp;
        }
    }
}
