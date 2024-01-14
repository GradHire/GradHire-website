<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Convention;

class ConventionRepository extends AbstractRepository
{
    protected static string $table = "Convention";

    /**
     * @throws ServerErrorException
     */
    public static function getStudentId(int $conventionId): int|null
    {
        $data = self::Fetch("SELECT idutilisateur FROM convention WHERE numconvention=?", [$conventionId]);
        return $data ? $data["idutilisateur"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getAddress(int $conventionId): string|null
    {
        $data = self::Fetch("SELECT adresse, codepostal, nomville FROM entreprise e JOIN offre o ON o.idutilisateur = e.idutilisateur JOIN convention c ON c.idoffre = o.idoffre JOIN ville vi ON e.idville = vi.idville WHERE c.numconvention=?", [$conventionId]);
        return $data ? $data["adresse"] . ", " . $data["codepostal"] . " " . $data["nomville"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIdByStudent(int $id): int|null
    {
        $data = self::Fetch("SELECT numconvention FROM convention WHERE idutilisateur=?", [$id]);
        return $data ? $data["numconvention"] : null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function exist(int $numConvention): bool
    {
        return self::CheckExist("SELECT numconvention FROM convention WHERE numconvention=?", [$numConvention]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getByNumConvention(int $numConvention): array|null
    {
        return self::Fetch("SELECT * FROM \"conventionValideVue\" WHERE numconvention=?", [$numConvention]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getConventionXOffreById(mixed $id): ?array
    {
        return self::Fetch("SELECT c.idutilisateur as idetudiant, c.idoffre, o.idutilisateur, o.sujet FROM convention c JOIN Offre o ON c.idoffre = o.idoffre WHERE numconvention=?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function validerPedagogiquement(int $id): void
    {
        self::Execute("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 1 WHERE numconvention = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getById(int $id): ?array
    {
        return self::Fetch("SELECT * FROM " . static::$table . " WHERE numconvention = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function unvalidatePedagogiquement(mixed $id): void
    {
        self::Execute("UPDATE " . static::$table . " SET conventionvalideepedagogiquement = 0 WHERE numconvention = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function unvalidate(mixed $id): void
    {
        self::Execute("UPDATE " . static::$table . " SET conventionvalidee = 0 WHERE numconvention = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getIfIdTuteurs(int $numconvention, int $idtuteur): bool
    {
        $data = self::Fetch("SELECT idtuteurprof, idtuteurentreprise FROM \"conventionValideVue\" WHERE numconvention=?", [$numconvention]);
        return $data && ($data["idtuteurprof"] == $idtuteur || $data["idtuteurentreprise"] == $idtuteur);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getInformationByNumConvention(int $numconvention): array
    {
        return self::Fetch("SELECT * FROM \"conventionValideVue\" WHERE numconvention=?", [$numconvention]) ?? [];
    }

    /**
     * @throws ServerErrorException
     */
    public static function validate(mixed $id): void
    {
        self::Execute("UPDATE " . static::$table . " SET conventionvalidee = 1 WHERE numconvention = ?", [$id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function imOneOfTheTutor(int $id, $numconvention): bool
    {
        $data = self::Fetch("SELECT idtuteurprof, idtuteurentreprise FROM \"conventionValideVue\" WHERE numconvention=?", [$numconvention]);
        return $data && ($data["idtuteurprof"] == $id || $data["idtuteurentreprise"] == $id);
    }

    /**
     * @throws ServerErrorException
     */
    public function getAll(): array
    {
        $data = self::FetchAll("SELECT * FROM " . static::$table) ?? [];
        $conventions = [];
        foreach ($data as $row)
            $conventions[] = $this->construireDepuisTableau($row);
        return $conventions;
    }

    public function construireDepuisTableau(array $dataObjectFormatTableau): Convention
    {
        return new Convention(
            $dataObjectFormatTableau
        );
    }

    /**
     * @throws ServerErrorException
     */
    public function getPourcentageEtudiantsConventionCetteAnnee(): false|array
    {
        return self::Fetch("SELECT * FROM pourcentage_etudiants_convention_cette_annee_cache;");
    }

    public function getConvention(): array
    {
        $data = self::FetchAll("SELECT numconvention,origineconvention,conventionvalidee,conventionvalideepedagogiquement,idutilisateur,idoffre FROM " . static::$table) ?? [];
        return $data;
    }


    protected function getNomTable(): string
    {
        return static::$table;
    }

    protected function getNomColonnes(): array
    {
        return [
            "numconvention",
            "origineconvention",
            "conventionvalidee",
            "conventionvalideepedagogiquement",
            "datemodification",
            "datecreation",
            "idsignataire",
            "idinterruption",
            "idutilisateur",
            "idoffre",
            "commentaire"
        ];
    }
}
