<?php

namespace app\src\model\dataObject;

class Supervise extends AbstractDataObject
{
    private int $idStudent;
    private int $idTeacher;
    private int $idTutor;
    private int $idOffre;
    private string $statut;

    public function __construct(array $attributes)
    {
        $this->idStudent = $attributes["idetudiant"];
        $this->idOffre = $attributes["idoffre"];
        $this->idTutor = $attributes["idtuteurentreprise"];
        $this->idTeacher = $attributes["idutilisateur"];
        $this->statut = $attributes["statut"];
    }

    public function getIdStudent(): int
    {
        return $this->idStudent;
    }

    public function setIdStudent(int $idStudent): void
    {
        $this->idStudent = $idStudent;
    }

    public function getIdTeacher(): int
    {
        return $this->idTeacher;
    }

    public function setIdTeacher(int $idTeacher): void
    {
        $this->idTeacher = $idTeacher;
    }

    public function getIdTutor(): int
    {
        return $this->idTutor;
    }

    public function setIdTutor(int $idTutor): void
    {
        $this->idTutor = $idTutor;
    }

    public function getIdOffre(): int
    {
        return $this->idOffre;
    }

    public function setIdOffre(int $idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}