<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Utilisateur;
use app\src\model\dataObject\Roles;
use mysql_xdevapi\DatabaseObject;
use PDOException;

class UtilisateurRepository extends AbstractRepository
{

    private string $nomTable = "Utilisateur";
    protected static string $view = '';
    protected static string $id_attributes = '';
    protected static string $create_function = '';

    protected static string $update_function = '';
    protected int $id;
    protected array $attributes;

    public function __construct(array $attributes)
    {
        if (count($attributes) == 0) return;
        $this->attributes = $attributes;
        $this->id = $attributes["idutilisateur"] ?? 0;
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

    /**
     * @throws ServerErrorException
     */
    public function getUserById($idUtilisateur): ?Utilisateur
    {
        try {
            $sql = "SELECT * FROM $this->nomTable WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $idUtilisateur]);
            $requete->setFetchMode(\PDO::FETCH_ASSOC);
            $resultat = $requete->fetch();
            if ($resultat == false) {
                return null;
            }
            return $this->construireDepuisTableau($resultat);
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Utilisateur
    {
        return new Utilisateur(
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['emailutilisateur'] ?? "",
            $dataObjectFormatTableau['nomutilisateur'] ?? "",
            $dataObjectFormatTableau['numtelutilisateur'] ?? "",
            $dataObjectFormatTableau['bio'] ?? "",
        );
    }

    protected function getNomColonnes(): array
    {
        return [
            "numtelutilisateur",
            "nomutilisateur",
            "emailutilisateur",
            "idutilisateur"
        ];
    }

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }

    /**
     * @throws ServerErrorException
     */
    public function setUserToArchived(Utilisateur $user, bool $bool): void
    {
        try {
            $sql = "UPDATE Utilisateur SET archiver = :bool WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $values = [
                'idutilisateur' => $user->getIdutilisateur(),
                'bool' => $bool ? 1 : 0
            ];
            $requete->execute($values);
            echo "L'utilisateur a été archivé";
        } catch (PDOException) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function isArchived(Utilisateur $utilisateur): ?bool{
        try {
            $sql = "SELECT archiver FROM Utilisateur WHERE idutilisateur = :idutilisateur";
            $requete = Database::get_conn()->prepare($sql);
            $requete->execute(['idutilisateur' => $utilisateur->getIdutilisateur()]);
            $resultat = $requete->fetch();
            if ($resultat == false) {
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
    public static function find_by_attribute(string $value): null|static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE " . static::$id_attributes . " = ?");
            $statement->execute([$value]);
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
    public static function save(array $values): static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT " . static::$create_function . "(" . ltrim(str_repeat(",?", count($values)), ",") . ") FROM DUAL");
            $statement->execute($values);
            return static::find_by_id(intval($statement->fetchColumn()));
        } catch (\Exception $e) {
            print_r($e);
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function find_by_id(string $id): null|static
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idutilisateur = ?");
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
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE idutilisateur = ?");
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

    public function role(): ?Roles
    {
        return null;
    }

    public function get_picture(): string
    {
        if (file_exists("./pictures/" . $this->id . ".jpg")) return "/pictures/" . $this->id . ".jpg";
        return "https://as2.ftcdn.net/v2/jpg/00/64/67/63/1000_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg";
    }

    public function full_name(): string
    {
        return $this->attributes["nomutilisateur"];
    }

    public function archived(): bool
    {
        return $this->attributes["archiver"] === 1;
    }

    public function getId()
    {
        return $this->id;
    }
}