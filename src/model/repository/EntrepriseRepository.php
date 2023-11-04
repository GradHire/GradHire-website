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
            $dataObjectFormatTableau['adresse'] ?? ""
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
            "adresse"
        ];
    }
}