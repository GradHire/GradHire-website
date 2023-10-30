<?php

namespace app\src\model\dataObject;

class TuteurEntreprise extends Utilisateur
{
    private int $idutilisateur;
    private ?string $prenomtuteurp;
    private ?string $fonctiontuteurp;
    private int $identreprise;

    public function __construct(int $idutilisateur, string $prenom, string $fonction, int $identreprise, string $emailutilisateur, string $nomutilisateur, string $numtelutilisateur)
    {
        parent::__construct($idutilisateur, $emailutilisateur, $nomutilisateur, $numtelutilisateur, "");
        $this->idUtilisateur = $idutilisateur;
        $this->prenom = $prenom;
        $this->fonction = $fonction;
        $this->idEntreprise = $identreprise;
    }

    public function getIdutilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idUtilisateur = $idutilisateur;
    }

    public function getPrenomtuteurp(): ?string
    {
        return $this->prenom;
    }

    public function setPrenomtuteurp(?string $prenomtuteurp): void
    {
        $this->prenom = $prenomtuteurp;
    }

    public function getFonctiontuteurp(): ?string
    {
        return $this->fonction;
    }

    public function setFonctiontuteurp(?string $fonctiontuteurp): void
    {
        $this->fonction = $fonctiontuteurp;
    }

    public function getIdentreprise(): int
    {
        return $this->idEntreprise;
    }

    public function setIdentreprise(int $identreprise): void
    {
        $this->idEntreprise = $identreprise;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

}