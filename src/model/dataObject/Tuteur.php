<?php

namespace app\src\model\dataObject;

use app\src\model\dataObject\Utilisateur;

class Tuteur extends Utilisateur
{

    private int $idUtilisateur;
    private string $prenom;
    private ?string $fonction;

    public function __construct(int $idUtilisateur, ?string $bio, string $emailUtilisateur, string $nomUtilisateur, ?string $numTelUtilisateur, string $prenomtuteurp, ?string $fonctiontuteurp)
    {
        parent::__construct($idUtilisateur, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur, $bio);
        $this->idUtilisateur = $idUtilisateur;
        $this->prenom = $prenomtuteurp;
        $this->fonction = $fonctiontuteurp;
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