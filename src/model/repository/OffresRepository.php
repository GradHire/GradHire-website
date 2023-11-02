<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Offre;
use PDOException;


class OffresRepository extends AbstractRepository
{

    private string $nomTable = "Offre";

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

        if ($sql['gratificationMin'] == null && $sql['gratificationMax'] == null) return $sql;
        else if ($sql['gratificationMin'] != null && $sql['gratificationMax'] == null) $gratificationMaxTemp = 15;
        else if ($sql['gratificationMin'] == null && $sql['gratificationMax'] != null) $gratificationMinTemp = 4.05;

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
                } else {
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

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if ($resultat == false) return null;
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function deleteById($idOffre): bool
    {
        try {
            $sql = "DELETE FROM $this->nomTable WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $idOffre]);
            return true;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function updateToDraft($idOffre): bool
    {
        try {
            $sql = "UPDATE $this->nomTable SET statut = 'draft' WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $idOffre]);
            return true;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getById($idOffre): ?Offre
    {
        try {
            $sql = "SELECT * FROM $this->nomTable WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $idOffre]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat == false) return null;
            return $this->construireDepuisTableau($resultat);
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Offre
    {
        if (!isset($dataObjectFormatTableau['nom'])) $dataObjectFormatTableau['nom'] = null;
        return new Offre(
            $dataObjectFormatTableau['idoffre'],
            $dataObjectFormatTableau['duree'],
            $dataObjectFormatTableau['thematique'],
            $dataObjectFormatTableau['sujet'],
            $dataObjectFormatTableau['nbjourtravailhebdo'],
            $dataObjectFormatTableau['nbheuretravailhebdo'],
            $dataObjectFormatTableau['gratification'],
            $dataObjectFormatTableau['avantagesnature'],
            $dataObjectFormatTableau['datedebut'],
            $dataObjectFormatTableau['datefin'],
            $dataObjectFormatTableau['statut'],
            $dataObjectFormatTableau['pourvue'],
            $dataObjectFormatTableau['anneevisee'],
            $dataObjectFormatTableau['annee'],
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['datecreation'],
            $dataObjectFormatTableau['description']
        );
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getByIdWithUser($idOffre): ?Offre
    {
        try {
            $sql = "SELECT * FROM $this->nomTable JOIN Utilisateur ON $this->nomTable.idUtilisateur = Utilisateur.idUtilisateur WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $idOffre]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) return null;
            return $this->construireDepuisTableau($resultat);
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getOffresByIdEntreprise($idEntreprise): ?array
    {
        try {
            $sql = "SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $idEntreprise]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            if ($resultat == false) return null;
            $offres = [];
            foreach ($resultat as $offre_data) {
                $offres[] = $this->construireDepuisTableau($offre_data);
            }
            return $offres;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function draftExist($idEntreprise): array
    {
        try {
            $sql = "SELECT * FROM Offre WHERE idUtilisateur = :idUtilisateur AND statut = 'draft'";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $idEntreprise]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetchAll();
            $offres = [];
            $anneeencours = date("Y");
            $datecreation = date("Y-m-d");
            $iduser = Application::getUser()->Id();
            $offres[] = new Offre(null, null, null, "", 7, 5, 4.05, null, null, $datecreation, $datecreation, null, null, $anneeencours, $iduser);
            if (!$resultat) return $offres;
            foreach ($resultat as $offre_data) {
                $offres[] = $this->construireDepuisTableau($offre_data);
            }
            return $offres;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    public
    function tableChecker($filter): string
    {
        if (array_key_exists('alternance', $filter) && array_key_exists('stage', $filter))
            return OffresRepository::getNomTable();
        else if (array_key_exists('alternance', $filter) && $filter['alternance'] != "")
            return "OffreAlternance JOIN Offre ON OffreAlternance.idoffre = Offre.idoffre";
        else if (array_key_exists('stage', $filter) && $filter['stage'] != "")
            return "OffreStage JOIN Offre ON OffreStage.idoffre = Offre.idoffre";
        else return OffresRepository::getNomTable();
    }

    protected
    function getNomTable(): string
    {
        return $this->nomTable;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function updateToApproved(mixed $id)
    {
        try {
            $sql = "UPDATE $this->nomTable SET statut = 'approved' WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $id]);
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function checkIfCreatorOffreIsArchived(Offre $offre): bool
    {
        try {
            $sql = "SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $offre->getIdoffre()]);
            $resultat = $requete->fetch();
            if ($resultat['archiver'] == 1) return true;
            else return false;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function checkArchived(Offre $offre): bool
    {
        try {
            $sql = "SELECT archiver FROM Offre o JOIN Utilisateur u ON u.idUtilisateur = o.idUtilisateur WHERE idoffre = :idoffre";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $offre->getIdoffre()]);
            $resultat = $requete->fetch();
            if ($resultat['archiver'] == 1) return true;
            else return false;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    function checkIfUserPostuled(Offre $offre): bool
    {
        try {
            $sql = "SELECT * FROM Postuler WHERE idoffre = :idoffre AND idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idoffre' => $offre->getIdoffre(), 'idUtilisateur' => Auth::get_user()->id()]);
            $resultat = $requete->fetch();
            if ($resultat == false) return false;
            else return true;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "duree",
            "thematique",
            "sujet",
            "nbJourTravailHebdo",
            "nbheureTravailhebdo",
            "gratification",
            "uniteGratification",
            "avantageNature",
            "dateDebut",
            "dateFin",
            "statut",
            "anneeVisee",
            "datecreation"
        ];
    }

    protected
    function checkFilterNotEmpty(array $filter): bool
    {
        foreach ($filter as $key => $value) if ($value != "") return true;
        return false;
    }
}
