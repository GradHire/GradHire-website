<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Utilisateur;
use PDOException;

class UtilisateurRepository extends AbstractRepository
{

    protected static string $view = '';
    protected static string $id_attributes = '';
    protected static string $create_function = '';
    protected static string $update_function = '';
    public int $id;
    public array $attributes;
    private string $nomTable = "Utilisateur";

    public function __construct(array $attributes)
    {
        if (count($attributes) == 0) return;
        $this->attributes = $attributes;
        $this->setId($attributes["idutilisateur"]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function find_by_attribute(string $value): null|static
    {
        $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE " . static::$id_attributes . " = ?");
        $statement->execute([$value]);

        $user = $statement->fetch();
        if (is_null($user) || $user === false) return null;
        return new static($user);
    }

    /**
     * @throws ServerErrorException
     */
    public
    static function save(array $values): static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * from " . static::$create_function . "(" . ltrim(str_repeat(",?", count($values)), ",") . ");");
            $statement->execute($values);
            return static::find_by_id(intval($statement->fetchColumn()));
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function find_by_id(string $id): null|static
    {

        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idUtilisateur = ?");
            $statement->execute([$id]);
            $user = $statement->fetch();
            if (is_null($user) || $user === false) return null;
            return new static($user);
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function getAll(): ?array
    {
        try {
            $sql = "SELECT * FROM $this->nomTable";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute();
            $resultat = $requete->fetchAll();
            $utilisateurs = [];
            foreach ($resultat as $utilisateur) {
                $utilisateurs[] = $this->construireDepuisTableau($utilisateur);
            }
            return $utilisateurs;
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Utilisateur
    {
        return new Utilisateur(
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['email'] ?? "",
            $dataObjectFormatTableau['nom'] ?? "",
            $dataObjectFormatTableau['numtelephone'] ?? "",
            $dataObjectFormatTableau['bio'] ?? "",
            $dataObjectFormatTableau['configuration'] ?? [],
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getUserById($idUtilisateur): ?Utilisateur
    {
        try {
            $sql = "SELECT * FROM $this->nomTable WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $idUtilisateur]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function setUserToArchived(Utilisateur $user, bool $bool): void
    {
        try {
            $sql = "UPDATE Utilisateur SET archiver = :bool WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $values = [
                'idUtilisateur' => $user->getIdutilisateur(),
                'bool' => $bool ? 1 : 0
            ];
            $requete->execute($values);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function isArchived(Utilisateur $utilisateur): ?bool
    {
        try {
            $sql = "SELECT archiver FROM Utilisateur WHERE idUtilisateur = :idUtilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idUtilisateur' => $utilisateur->getIdutilisateur()]);
            $resultat = $requete->fetch();
            if (!$resultat) {
                return null;
            }
            return $resultat['archiver'];
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function update(array $values): void
    {
        try {
            $statement = Database::get_conn()->prepare("CALL " . static::$update_function . "(" . $this->id . ", " . ltrim(str_repeat(",?", count($values)), ",") . ")");
            $statement->execute(array_values($values));
            if ($this->is_me()) {
                $this->refresh();
                Application::setUser($this);
            }
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function is_me(): bool
    {
        if (Application::isGuest()) return false;
        if (is_null(Application::getUser())) return false;
        return Application::getUser()->id() === $this->id;
    }

    public function id(): int
    {
        return $this->id;
    }

    /**
     * @throws ServerErrorException
     */
    public function refresh(): void
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idUtilisateur = ?");
            $statement->execute([$this->id]);
            $user = $statement->fetch();
            if (is_null($user)) throw new ServerErrorException();
            $this->attributes = $user;
        } catch (\Exception) {
            throw new ServerErrorException();
        }
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @throws ServerErrorException
     */
    public function role(): ?Roles
    {
        if ((new StaffRepository([]))->getByIdFull($this->id) !== null) return (new StaffRepository([]))->role($this->id);
        else if ((new EtudiantRepository([]))->getByIdFull($this->id) !== null) return (new EtudiantRepository([]))->role($this->id);
        else if ((new TuteurRepository([]))->getByIdFull($this->id) !== null) return (new TuteurRepository([]))->role($this->id);
        else if ((new EntrepriseRepository([]))->getByIdFull($this->id) !== null) return (new EntrepriseRepository([]))->role($this->id);
        else return null;
    }

    public function get_picture(): string
    {
        if (file_exists("./pictures/" . $this->id . ".jpg")) return "/pictures/" . $this->id . ".jpg";
        return "https://as2.ftcdn.net/v2/jpg/00/64/67/63/1000_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg";
    }

    public function get_configuration(): ?array
    {
        return $this->attributes["configuration"];
    }

    public function full_name(): string
    {
        return ($this->attributes["prenom"] ?? "") . " " . $this->attributes["nom"];
    }

    public function archived(): bool
    {
        return $this->attributes["archiver"] === 1;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    protected function getNomColonnes(): array
    {
        return [
            "numTelephone",
            "nom",
            "email",
            "idUtilisateur"
        ];
    }

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }
}