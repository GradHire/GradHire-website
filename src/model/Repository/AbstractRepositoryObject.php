<?php


namespace app\src\model\Repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\DataObject\AbstractDataObject;

abstract class AbstractRepositoryObject
{
    /**
     * @throws ServerErrorException
     */
    public function search(string $search, array $filter): array
    {
        //requete prepare stp
        echo $this->getNomTable();
        echo $search;
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE sujet = :sujetTag";
        if (!empty($filter)) {
            print_r($filter);
            foreach ($filter as $key => $value) {
                if ($value == true) {
                    $sql .= " AND " . $key . " = :" . $key . "Tag";
                }
            }
        }

        $pdoStatement = Database::get_conn()->prepare($sql);
        print_r($pdoStatement);
        $values = [];
        foreach ($filter as $key => $value) {
            if ($value == true) {
                echo $key . ' : ' . $value . ' ';
                $values[$key . "Tag"] = $value;
            }
        }
        echo 'rechercher : ' . $search . ' ';
        $values["sujetTag"] = $search;

        $pdoStatement->execute($values);
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

}
