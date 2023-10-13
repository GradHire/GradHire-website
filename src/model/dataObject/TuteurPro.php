<?php

namespace app\src\model\dataObject;

class TuteurPro extends Utilisateur
{
    private int $idutilisateur;
    private ?string $prenomtuteurp;
    private ?string $fonctiontuteurp;
    private int $identreprise;

    public function __construct(int $idutilisateur,string $prenom,string $fonction,int $identreprise, string $emailutilisateur, string $nomutilisateur, string $numtelutilisateur)
    {
        parent::__construct($idutilisateur, $emailutilisateur, $nomutilisateur, $numtelutilisateur);
        $this->idutilisateur = $idutilisateur;
        $this->prenomtuteurp=$prenom;
        $this->fonctiontuteurp=$fonction;
        $this->identreprise=$identreprise;
    }
    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getPrenomtuteurp(): ?string
    {
        return $this->prenomtuteurp;
    }

    public function setPrenomtuteurp(?string $prenomtuteurp): void
    {
        $this->prenomtuteurp = $prenomtuteurp;
    }

    public function getFonctiontuteurp(): ?string
    {
        return $this->fonctiontuteurp;
    }

    public function setFonctiontuteurp(?string $fonctiontuteurp): void
    {
        $this->fonctiontuteurp = $fonctiontuteurp;
    }

    public function getIdentreprise(): int
    {
        return $this->identreprise;
    }

    public function setIdentreprise(int $identreprise): void
    {
        $this->identreprise = $identreprise;
    }

}