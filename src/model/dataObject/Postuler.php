<?php

namespace app\src\model\dataObject;

use app\src\model\repository\PostulerRepository;

class Postuler extends AbstractDataObject
{
    private ?string $sujet;
    private ?string $nom;
    private ?string $dates;
    private ?int $idoffre;
    private ?int $idutilisateur;
    private ?int $identreprise;
    private ?string $statut;

    public function __construct(
        array $attributes
    )
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getIdEntreprise(): ?int
    {
        return $this->identreprise;
    }

    public function setIdEntreprise(?int $idEntreprise): void
    {
        $this->identreprise = $idEntreprise;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
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

    public function setStatutPostuler(string $etat): void
    {
        (new PostulerRepository())->setStatutPostuler($this->getIdUtilisateur(), $this->getIdOffre(), $etat);
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idutilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idutilisateur = $idUtilisateur;
    }

    public function getIdOffre(): ?int
    {
        return $this->idoffre;
    }

    public function setIdOffre(?int $idOffre): void
    {
        $this->idoffre = $idOffre;
    }

    public function getIfSuivi(int $idUtilisateur): bool
    {
        return (new PostulerRepository())->getIfSuivi($idUtilisateur, $this->getIdUtilisateur(), $this->getIdOffre());
    }

    public function getSiTuteurPostuler(): bool
    {
        return (new PostulerRepository())->getSiTuteurPostuler($this->getIdUtilisateur(), $this->getIdOffre());
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }
}