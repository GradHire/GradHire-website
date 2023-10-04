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
    public function search(string $search, array|null $filter): array
    {
        //filtre but 2eme, 3eme; theme; gratification;
        if ($search == "") {
            return $this->recuperer();
        }
        //requete prepare stp
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE sujet =: Tag";
        if ($filter != null) {
            foreach ($filter as $key => $value) {
                if ($value == true) {
                    $sql .= " AND " . $key . " = 1";
                }
            }
        }
        $pdoStatement = Database::get_conn()->prepare($sql);
        $values = array(
            "Tag" => $search,
        );
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
