<?php

namespace app\src\model\dataObject;

use app\src\model\repository\CandidatureRepository;

class Candidature extends AbstractDataObject
{
    private ?int $idcandidature;
    private ?string $datecandidature;
    private ?string $etatcandidature;
    private int $idoffre;
    private int $idutilisateur;

    /**
     * @param int|null $idcandidature
     * @param string $datecandidature
     * @param string $etatcandidature
     * @param string $idoffre
     * @param int $idutilisateur
     */
    public function __construct(?int $idcandidature, ?string $datecandidature, ?string $etatcandidature, int $idoffre, int $idutilisateur)
    {
        $this->idcandidature = $idcandidature;
        $this->datecandidature = $datecandidature;
        $this->etatcandidature = $etatcandidature;
        $this->idoffre = $idoffre;
        $this->idUtilisateur = $idutilisateur;
    }

    public function getIdcandidature(): ?int
    {
        return $this->idcandidature;
    }

    public function setIdcandidature(?int $idcandidature): void
    {
        $this->idcandidature = $idcandidature;
    }

    public function getDatecandidature(): ?string
    {
        return $this->datecandidature;
    }

    public function setDatecandidature(?string $datecandidature): void
    {
        $this->datecandidature = $datecandidature;
    }

    public function getEtatcandidature(): ?string
    {
        return $this->etatcandidature;
    }

    public function setEtatcandidature(string $etatcandidature): void
    {
        $this->etatcandidature = $etatcandidature;
        (new CandidatureRepository())->setEtatCandidature($this->idcandidature, $etatcandidature);
    }

    public function getIdoffre(): int
    {
        return $this->idoffre;
    }

    public function setIdoffre(int $idoffre): void
    {
        $this->idoffre = $idoffre;
    }

    public function getIdutilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idUtilisateur = $idutilisateur;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }


}