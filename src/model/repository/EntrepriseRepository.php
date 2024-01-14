<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\Entreprise;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use Exception;

class EntrepriseRepository extends ProRepository
{
    protected static string $view = "entreprisevue";
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
            $hash = self::hashPassword($body["password"]);
            $user = self::save([$body["name"], $body["siret"], $body["email"], $hash, $body["phone"]]);
            $emails = (new StaffRepository([]))->getManagersEmail();
            MailRepository::send_mail($emails, "Nouvelle entreprise",
                '<div>
 <p>L\'entreprise ' . $body["name"] . ' viens de créer un compte</p>
 <a href="' . HOST . '/entreprises/' . $user->id() . '">Voir le profile</a>
 </div>');
            Auth::generate_token($user, false);
            return true;
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function exist(string $email, string $siret): bool
    {
        return self::CheckExist("SELECT * FROM " . static::$view . " WHERE email=? OR siret=?", [$email, $siret]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTuteurWaitingList(string $id): array
    {
        return self::FetchAllAssoc("SELECT * FROM CreationCompteTuteur WHERE idUtilisateur = ?", [$id]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getList(): array
    {
        return self::FetchAll("SELECT idUtilisateur, nom FROM EntrepriseVue") ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function getEmailById($id): ?array
    {
        return self::Fetch("SELECT email FROM hirchytsd.entreprisevue WHERE idutilisateur = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getNomEntrepriseById(int $idUtilisateur): ?string
    {
        $data = self::FetchAssoc("SELECT nom FROM " . self::$view . " WHERE idUtilisateur = ?", [$idUtilisateur]);
        return $data ? $data["nom"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIdEntrepriseByIdOffre(int $id): int
    {
        $data = self::FetchAssoc("SELECT idUtilisateur FROM " . self::$view . " WHERE idOffre = ?", [$id]);
        return $data ? $data["idUtilisateur"] : 0;
    }

    public function role(): Roles
    {
        return Roles::Enterprise;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByIdFull($idEntreprise): ?Entreprise
    {
        $data = self::FetchAssoc("SELECT * FROM " . $this->getNomTable() . " WHERE " . $this->getNomTable() . ".idUtilisateur = ?", [$idEntreprise]);
        return $data ? $this->construireDepuisTableau($data) : null;
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
            $dataObjectFormatTableau
        );
    }

    /**
     * @throws ServerErrorException
     */
    public
    function getAll(): ?array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " e JOIN Utilisateur u ON e.idUtilisateur = u.idUtilisateur";
        $data = self::FetchAll($sql);
        if (!$data) return null;
        $entreprises = [];
        foreach ($data as $entrepriseData)
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        return $entreprises;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByName(mixed $nomEnt, mixed $pays, mixed $department): ?array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE nom = :nomEnt AND pays = :pays AND codePostal LIKE :department";
        $data = self::FetchAllAssoc($sql, ['nomEnt' => $nomEnt, 'pays' => $pays, 'department' => $department . '%']);
        if (!$data) return null;
        $entreprises = [];
        foreach ($data as $entrepriseData)
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        return $entreprises;
    }

    /**
     * @throws ServerErrorException
     */
    public function getBySiret(mixed $siret, mixed $siren): ?array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE siret = :siret AND siret LIKE  :siren";
        $data = self::FetchAllAssoc($sql, ['siret' => $siret, 'siren' => $siren . '%']);
        if (!$data) return null;
        $entreprises = [];
        foreach ($data as $entrepriseData)
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        return $entreprises;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByTel(mixed $tel, mixed $fax): ?array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE numTelephone = :tel AND fax = :fax";
        $data = self::FetchAllAssoc($sql, ['tel' => $tel, 'fax' => $fax]);
        if (!$data) return null;
        $entreprises = [];
        foreach ($data as $entrepriseData)
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        return $entreprises;
    }

    /**
     * @throws ServerErrorException
     */
    public function getByAdresse(mixed $adresse, mixed $codePostal, mixed $pays): ?array
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE adresse = :adresse AND codePostal = :codePostal AND pays = :pays";
        $data = self::FetchAllAssoc($sql, ['adresse' => $adresse, 'codePostal' => $codePostal, 'pays' => $pays]);
        if (!$data) return null;
        $entreprises = [];
        foreach ($data as $entrepriseData)
            $entreprises[] = $this->construireDepuisTableau($entrepriseData);
        return $entreprises;
    }

    /**
     * @throws ServerErrorException
     */
    public function getCodePostal(int $idUtilisateur): ?string
    {
        $sql = "SELECT codePostal FROM " . $this->getNomTable() . " WHERE idUtilisateur = :idUtilisateur";
        $data = self::FetchAssoc($sql, ['idUtilisateur' => $idUtilisateur]);
        return $data ? $data["codePostal"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public function getVille(int $idUtilisateur): ?string
    {
        $sql = "SELECT nomVille FROM " . $this->getNomTable() . "  WHERE idUtilisateur = :idUtilisateur";
        $data = self::FetchAssoc($sql, ['idUtilisateur' => $idUtilisateur]);
        return $data ? $data["nomVille"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public function getPays(int $idUtilisateur): ?string
    {
        $sql = "SELECT pays FROM " . $this->getNomTable() . " WHERE idUtilisateur = :idUtilisateur";
        $data = self::FetchAssoc($sql, ['idUtilisateur' => $idUtilisateur]);
        return $data ? $data["pays"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public function create(mixed $nomEnt, mixed $email, mixed $tel, string $string, mixed $type, mixed $effectif, mixed $codeNaf, mixed $fax, mixed $web, mixed $voie, mixed $cedex, mixed $residence, mixed $codePostal, mixed $pays, mixed $nomville, mixed $siret): void
    {
        $sql = "SELECT creerentimp(:nomEnt, :email, :tel, :string, :type, :effectif, :codeNaf, :fax, :web, :voie, :cedex, :residence, :codePostal, :pays, :nomville, :siret) ";
        self::Execute($sql, [
            'nomEnt' => $nomEnt,
            'email' => $email,
            'tel' => $tel,
            'string' => $string,
            'type' => $type,
            'effectif' => $effectif,
            'codeNaf' => $codeNaf,
            'fax' => $fax,
            'web' => $web,
            'voie' => $voie,
            'cedex' => $cedex,
            'residence' => $residence,
            'codePostal' => $codePostal,
            'pays' => $pays,
            'nomville' => $nomville,
            'siret' => $siret
        ]);
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
