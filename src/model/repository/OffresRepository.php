<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\model\dataObject\Offre;


class OffresRepository extends AbstractRepository
{

    private string $nomTable = "Offre";

    public function recupererParId($idOffre): ?Offre
    {
        $sql = "SELECT * FROM $this->nomTable WHERE idoffre = :idoffre";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idoffre' => $idOffre]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if ($resultat == false) {
            return null;
        }
        return $this->construireDepuisTableau($resultat);
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Offre
    {
        return new Offre(
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['duree'],
            $dataObjectFormatTableau['thematique'],
            $dataObjectFormatTableau['sujet'],
            $dataObjectFormatTableau['nbjourtravailhebdo'],
            $dataObjectFormatTableau['nbheuretravailhebdo'],
            $dataObjectFormatTableau['gratification'],
            $dataObjectFormatTableau['unitegratification'],
            $dataObjectFormatTableau['avantagenature'],
            $dataObjectFormatTableau['datedebut'],
            $dataObjectFormatTableau['datefin'],
            $dataObjectFormatTableau['statut'],
            $dataObjectFormatTableau['anneevisee'],
            $dataObjectFormatTableau['idannee'],
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['description'],
            $dataObjectFormatTableau['datecreation']
//            $dataObjectFormatTableau['alternance']
        );
    }

    protected function getNomColonnes(): array
    {
        return [
            "duree",
            "thematique",
            "sujet",
            "nbjourtravailhebdo",
            "nbheureTravailhebdo",
            "gratification",
            "unitegratification",
            "avantagenature",
            "datedebut",
            "datefin",
            "statut",
            "anneevisee",
            "datecreation"
        ];
    }

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }

    protected function checkFilterNotEmpty(array $filter): bool
    {
        foreach ($filter as $key => $value) {
            if ($value != "") return true;
        }
        return false;
    }

    public function tableChecker($filter): string
    {
        if (array_key_exists('alternance',$filter) && array_key_exists('stage',$filter)){
            return OffresRepository::getNomTable();
        }
        else if (array_key_exists('alternance', $filter) && $filter['alternance'] != "") {
            return "Offrealternance JOIN Offre ON Offrealternance.idoffre = Offre.idoffre";
        } else if (array_key_exists('stage', $filter) && $filter['stage'] != "") {
            return "Offrestage JOIN Offre ON Offrestage.idoffre = Offre.idoffre";
        } else {
            return OffresRepository::getNomTable();
        }
    }

    protected static function checkOnlyStageOrAlternance($filter): bool
    {
        $listCherker = ['alternance', 'stage', 'gratificationMin', 'gratificationMax', 'sujet'];
        //return true if it contains only stage or alternance
        if (array_key_exists('alternance', $filter) && array_key_exists('stage', $filter)) {
            return false;
        } else if (array_key_exists('alternance', $filter)) {
            foreach ($filter as $key => $value) {
                if (!in_array($key, $listCherker)) return false;
            }
        } else if (array_key_exists('stage', $filter)) {
            foreach ($filter as $key => $value) {
                if (!in_array($key, $listCherker)) return false;
            }
        }
        return false;
    }

    protected static function prepareSQLGratification(int $size, array $filter): array
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

    protected static function prepareSQLFilter(array $values): string
    {
        $sql = "";
        foreach ($values as $key => $value) {
            if ($key != 'gratificationMin' && $key != 'gratificationMax' && $key != 'sujet') {
                foreach ($value as $key2 => $value2) {
                    if ($key == 'thematique') {
                        if ($key2 == count($value) - 1) $sql .= $key . " = :" . $key . $key2 . "Tag AND ";
                        else $sql .= $key . " = :" . $key . $key2 . "Tag OR ";
                    } else {
                        if ($key2 == count($value) - 1) $sql .= $key . " = :" . $key . "Tag AND ";
                        else $sql .= $key . " = :" . $key . "Tag OR ";
                    }
                }
            }
        }
        return $sql;
    }

    protected static function prepareSQLQSearch(array $arraySearch): string
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

    protected static function removeEndifAlone(string $sql): string
    {
        if (substr($sql, -4) == "AND " || substr($sql, -3) == "OR ") $sql = substr($sql, 0, -4);
        return $sql;
    }

    protected static function constructSQLValues(?array $values, ?array $arraySearch, ?array $filter): array
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
