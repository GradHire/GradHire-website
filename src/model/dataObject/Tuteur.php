<?php

namespace app\src\model\dataObject;

class Tuteur extends Utilisateur
{

    private int $idUtilisateur;
    private int $archiver;
    private string $prenom;
    private ?string $fonction;
    private int $idEntreprise;
    private ?int $idOffre;

    public function __construct(int $idUtilisateur, string $emailUtilisateur, string $nomUtilisateur, ?string $numTelUtilisateur, ?string $bio,int $archiver, string $prenomtuteurp, ?string $fonctiontuteurp, int $idEntreprise, ?int $idOffre)
    {
        parent::__construct($idUtilisateur, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur, $bio);
        $this->idUtilisateur = $idUtilisateur;
        $this->prenom = $prenomtuteurp;
        $this->archiver = $archiver;
        $this->fonction = $fonctiontuteurp;
        $this->idEntreprise = $idEntreprise;
        $this->idOffre = $idOffre;
    }

    public function getArchiver(): int
    {
        return $this->archiver;
    }

    public function setArchiver(int $archiver): void
    {
        $this->archiver = $archiver;
    }

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function setIdOffre(?int $idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): void
    {
        $this->fonction = $fonction;
    }

    public function getIdEntreprise(): int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(int $idEntreprise): void
    {
        $this->idEntreprise = $idEntreprise;
    }
}