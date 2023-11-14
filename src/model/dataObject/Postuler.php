<?php

namespace app\src\model\dataObject;

use app\src\core\exception\ServerErrorException;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;

class Postuler extends AbstractDataObject
{
    private ?string $sujet;
    private ?string $nom;
    private ?string $dates;
    private ?int $idOffre;
    private ?int $idUtilisateur;
    private ?string $statut;

    public function __construct(?string $sujet, ?string $nom, ?string $dates, ?int $idOffre, ?int $idUtilisateur, ?string $statut = null)
    {
        $this->sujet = $sujet;
        $this->nom = $nom;
        $this->dates = $dates;
        $this->idOffre = $idOffre;
        $this->idUtilisateur = $idUtilisateur;
        $this->statut = $statut;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): void
    {
        $this->sujet = $sujet;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDates(): ?string
    {
        return $this->dates;
    }

    public function setDates(?string $dates): void
    {
        $this->dates = $dates;
    }

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function setIdOffre(?int $idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    public function setStatutPostuler(string $etat): void
    {
        (new PostulerRepository())->setStatutPostuler($this->getIdUtilisateur(), $this->getIdOffre(), $etat);
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function getIfSuivi(int $idUtilisateur): bool
    {
        return (new PostulerRepository())->getIfSuivi($idUtilisateur, $this->getIdUtilisateur(),$this->getIdOffre());
    }

    public function getSiTuteurPostuler(): bool {
        return (new PostulerRepository())->getSiTuteurPostuler($this->getIdUtilisateur(),$this->getIdOffre());
    }


}