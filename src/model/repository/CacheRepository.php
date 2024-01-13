<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;

class CacheRepository
{
    private static array $tutors = [];
    private static array $postulationsCount = [];
    private static array $tutor = [];

    /**
     * @throws ServerErrorException
     */
    public static function getTutorsByEntreprise(int $id): array
    {
        if (isset(self::$tutors[$id])) return self::$tutors[$id];
        $tutors = (new TuteurEntrepriseRepository([]))->getAllTuteursByIdEntreprise($id) ?? [];
        self::$tutors[$id] = $tutors;
        return $tutors;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getPostulationsCount(int $id): int
    {
        if (isset(self::$postulationsCount[$id])) return self::$postulationsCount[$id];
        $count = (new StaffRepository([]))->getCountPostulationTuteur($id);
        self::$postulationsCount[$id] = $count;
        return $count;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getTutorById(int $id): ?string
    {
        if (isset(self::$tutor[$id])) return self::$tutor[$id];
        $tutor = TuteurRepository::getNomsTuteurs();
        self::$tutor = $tutor;
        return $tutor[$id];
    }
}