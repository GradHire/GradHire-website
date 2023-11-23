<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use PDOException;

class EntrepriseRepository extends ProRepository
{
    protected static string $view = "EntrepriseVue";
    protected static string $create_function = "creerEntreprise";
    protected static string $update_function = "updateEntreprise";

    /**
     * @throws ServerErrorException
     */
    public static function register(array $body, FormModel $form): bool
    {
        try {
            $exist = self::exist($body["email"], $body["siret"]);
            if ($exist) {
                $form->setError("Un compte existe déjà avec cette adresse mail ou ce numéro de siret.");
                return false;
            }
            $user = self::save([$body["name"], $body["siret"], $body["email"], password_hash($body["password"], PASSWORD_DEFAULT), $body["phone"]]);
            $emails = (new StaffRepository([]))->getManagersEmail();
            MailRepository::send_mail($emails, "Nouvelle entreprise",
                '<div>
 <p>L\'entreprise ' . $body["name"] . ' viens de créer un compte</p>
 <a href="' . HOST . '/entreprises/' . $user->id() . '">Voir le profile</a>
 </div>');
            Auth::generate_token($user, false);
            return true;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public
    static function exist(string $email, string $siret): bool
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE email=? OR siret=?");
            $statement->execute([$email, $siret]);
            $count = $statement->rowCount();
            if ($count == 0) return false;
            return true;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTuteurWaitingList(string $id): array
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT email FROM CreationCompteTuteur WHERE idUtilisateur = ?");
            $statement->execute([$id]);
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $statement->fetchAll();
            if (!$resultat) {
                return [];
            }
            return $resultat;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function getList(): array
    {
        try {
            $sql = "SELECT idUtilisateur, nom FROM EntrepriseVue";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $resultat = $requete->fetchAll();
            if (!$resultat) {
                return [];
            }
            return $resultat;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    public
    function role(): Roles
    {
        return Roles::Enterprise;
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getByIdFull($idEntreprise): ?Entreprise
    {
        try {
            $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE " . $this->getNomTable() . ".idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $idEntreprise]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected
    function getNomTable(): string
    {
        return self::$view;
    }

    protected
    function construireDepuisTableau(array $dataObjectFormatTableau): Entreprise
    {
        return new Entreprise(
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['statutjuridique'] ?? "",
            $dataObjectFormatTableau['bio'] ?? "",
            $dataObjectFormatTableau['typestructure'] ?? "",
            $dataObjectFormatTableau['effectif'] ?? "",
            $dataObjectFormatTableau['codenaf'] ?? "",
            $dataObjectFormatTableau['fax'] ?? "",
            $dataObjectFormatTableau['siteweb'] ?? "",
            $dataObjectFormatTableau['siret'] ?? 0,
            $dataObjectFormatTableau['email'] ?? "",
            $dataObjectFormatTableau['nom'] ?? "",
            $dataObjectFormatTableau['numtelephone'] ?? "",
            $dataObjectFormatTableau['adresse'] ?? "",
            $dataObjectFormatTableau['codepostal'] ?? "",
            $dataObjectFormatTableau['nomville'] ?? "",
            $dataObjectFormatTableau['pays'] ?? ""
        );
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM " . $this->getNomTable() . " e JOIN Utilisateur u ON e.idUtilisateur = u.idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $resultat = $requete->fetchAll();
            if (!$resultat) {
                return null;
            }
            $entreprises = [];
            foreach ($resultat as $entrepriseData) {
                $entreprises[] = $this->construireDepuisTableau($entrepriseData);
            }
            return $entreprises;
        } catch
        (PDOException) {
            throw new ServerErrorException();
        }
    }

    public function getByName(mixed $nomEnt, mixed $pays, mixed $department)
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE nom = :nomEnt AND pays = :pays AND codePostal LIKE :department";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['nomEnt' => $nomEnt, 'pays' => $pays, 'department' => $department . '%']);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) {
            return null;
        }
        $entreprises = [];
        foreach ($resultat as $entrepriseData) {
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        }
        return $entreprises;
    }

    public function getBySiret(mixed $siret, mixed $siren)
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE siret = :siret AND siret LIKE  :siren";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['siret' => $siret, 'siren' => $siren . '%']);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) {
            return null;
        }
        $entreprises = [];
        foreach ($resultat as $entrepriseData) {
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        }
        return $entreprises;
    }

    public function getByTel(mixed $tel, mixed $fax)
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE numTelephone = :tel AND fax = :fax";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['tel' => $tel, 'fax' => $fax]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) {
            return null;
        }
        $entreprises = [];
        foreach ($resultat as $entrepriseData) {
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        }
        return $entreprises;
    }

    public function getByAdresse(mixed $adresse, mixed $codePostal, mixed $pays)
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE adresse = :adresse AND codePostal = :codePostal AND pays = :pays";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['adresse' => $adresse, 'codePostal' => $codePostal, 'pays' => $pays]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetchAll();
        if (!$resultat) {
            return null;
        }
        $entreprises = [];
        foreach ($resultat as $entrepriseData) {
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        }
        return $entreprises;
    }

    public function getCodePostal(int $idUtilisateur): ?string
    {
        $sql = "SELECT codePostal FROM " . $this->getNomTable() . " WHERE idUtilisateur = :idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) {
            return null;
        }
        return $resultat['codepostal'];
    }

    public function getVille(int $idUtilisateur): ?string
    {
        $sql = "SELECT nomVille FROM " . $this->getNomTable() . "  WHERE idUtilisateur = :idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) {
            return null;
        }
        return $resultat['nomville'];
    }

    public function getPays(int $idUtilisateur): ?string
    {
        $sql = "SELECT pays FROM " . $this->getNomTable() . " WHERE idUtilisateur = :idUtilisateur";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['idUtilisateur' => $idUtilisateur]);
        $requete->setFetchMode(\PDO::FETCH_ASSOC);
        $resultat = $requete->fetch();
        if (!$resultat) {
            return null;
        }
        return $resultat['pays'];
    }

    public function create(mixed $nomEnt, mixed $email, mixed $tel, string $string, mixed $type, mixed $effectif, mixed $codeNaf, mixed $fax, mixed $web, mixed $voie, mixed $cedex, mixed $residence, mixed $codePostal, mixed $pays, mixed $ville, mixed $siret)
    {
        $sql = "SELECT creerEntImp(:nomEnt, :email, :tel, :string, :type, :effectif, :codeNaf, :fax, :web, :voie, :cedex, :residence, :codePostal, :pays, :ville, :siret) ";
        $requete = Database::get_conn()->prepare($sql);
        $requete->execute(['nomEnt' => $nomEnt, 'email' => $email, 'tel' => $tel, 'string' => $string, 'type' => $type, 'effectif' => $effectif, 'codeNaf' => $codeNaf, 'fax' => $fax, 'web' => $web, 'voie' => $voie, 'cedex' => $cedex, 'residence' => $residence, 'codePostal' => $codePostal, 'pays' => $pays, 'nomville' => $ville, 'siret' => $siret]);
    }

    protected
    function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "statutJuridique",
            "bio",
            "typeStructure",
            "effectif",
            "codeNaf",
            "fax",
            "siteWeb",
            "siret",
            "email",
            "nom",
            "numTelephone",
            "adresse",
            "codePostal",
            "nomville",
            "pays"
        ];
    }
}