<?php

namespace app\src\model\dataObject;

class Candidature extends AbstractDataObject
{
private ?int $idcandidature;
private string $datecandidature;
private string $etatcandidature;
private string $idoffre;
private string $idutilisateur;

    /**
     * @param int|null $idcandidature
     * @param string $datecandidature
     * @param string $etatcandidature
     * @param string $idoffre
     * @param string $idutilisateur
     */
    public function __construct(?int $idcandidature, string $datecandidature, string $etatcandidature, string $idoffre, string $idutilisateur)
    {
        $this->idcandidature = $idcandidature;
        $this->datecandidature = $datecandidature;
        $this->etatcandidature = $etatcandidature;
        $this->idoffre = $idoffre;
        $this->idutilisateur = $idutilisateur;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function getIdcandidature(): ?int
    {
        return $this->idcandidature;
    }

    public function getDatecandidature(): string
    {
        return $this->datecandidature;
    }

    public function getEtatcandidature(): string
    {
        return $this->etatcandidature;
    }

    public function getIdoffre(): string
    {
        return $this->idoffre;
    }

    public function getIdutilisateur(): string
    {
        return $this->idutilisateur;
    }

}