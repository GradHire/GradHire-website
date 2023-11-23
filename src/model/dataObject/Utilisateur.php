<?php

namespace app\src\model\dataObject;

class Utilisateur extends AbstractDataObject
{
    private ?int $idUtilisateur;
    private ?string $numTelUtilisateur;
    private string $nomUtilisateur;
    private string $emailUtilisateur;
    private ?string $bio;
    private ?array $configuration;

    /**
     * @param string|null $numtelutilisateur
     * @param string $nomutilisateur
     * @param string $emailutilisateur
     * @param ?int $idutilisateur
     * @param ?array $configuration
     */
    public function __construct($idutilisateur, $emailutilisateur, $nomutilisateur, $numtelutilisateur, $bio, $configuration)
    {
        $this->idUtilisateur = $idutilisateur;
        $this->emailUtilisateur = $emailutilisateur;
        $this->nomUtilisateur = $nomutilisateur;
        $this->numTelUtilisateur = $numtelutilisateur;
        $this->bio = $bio;
        $this->configuration = $configuration;
    }

    public function getNumtelutilisateur(): ?string
    {
        return $this->numTelUtilisateur;
    }

    public function setNumtelutilisateur(?string $numtelutilisateur): void
    {
        $this->numTelUtilisateur = $numtelutilisateur;
    }

    public function getNomutilisateur(): string
    {
        return $this->nomUtilisateur;
    }

    public function setNomutilisateur(string $nomutilisateur): void
    {
        $this->nomUtilisateur = $nomutilisateur;
    }

    public function getEmailutilisateur(): string
    {
        return $this->emailUtilisateur;
    }

    public function setEmailutilisateur(string $emailutilisateur): void
    {
        $this->emailUtilisateur = $emailutilisateur;
    }

    public function getIdutilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idUtilisateur = $idutilisateur;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }

    public function setConfiguration(?array $configuration): void
    {
        $this->configuration = $configuration;
    }

}