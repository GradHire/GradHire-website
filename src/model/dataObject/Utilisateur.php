<?php

namespace app\src\model\dataObject;

class Utilisateur extends AbstractDataObject
{

    private ?string $numtelutilisateur;
    private string $nomutilisateur;
    private string $emailutilisateur;
    private int $idutilisateur;
    private ?string $bio;

    /**
     * @param string|null $numtelutilisateur
     * @param string $nomutilisateur
     * @param string $emailutilisateur
     * @param int $idutilisateur
     */
    public function __construct($idutilisateur, $emailutilisateur, $nomutilisateur, $numtelutilisateur, $bio)
    {
        $this->idutilisateur = $idutilisateur;
        $this->emailutilisateur = $emailutilisateur;
        $this->nomutilisateur = $nomutilisateur;
        $this->numtelutilisateur = $numtelutilisateur;
        $this->bio = $bio;
    }

    public function getNumtelutilisateur(): ?string
    {
        return $this->numtelutilisateur;
    }

    public function setNumtelutilisateur(?string $numtelutilisateur): void
    {
        $this->numtelutilisateur = $numtelutilisateur;
    }

    public function getNomutilisateur(): string
    {
        return $this->nomutilisateur;
    }

    public function setNomutilisateur(string $nomutilisateur): void
    {
        $this->nomutilisateur = $nomutilisateur;
    }

    public function getEmailutilisateur(): string
    {
        return $this->emailutilisateur;
    }

    public function setEmailutilisateur(string $emailutilisateur): void
    {
        $this->emailutilisateur = $emailutilisateur;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

}