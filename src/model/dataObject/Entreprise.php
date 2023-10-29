<?php

namespace app\src\model\dataObject;

class Entreprise extends Utilisateur
{
    private int $idutilisateur;
    private ?string $statutjuridique;
    private ?string $typestructure;
    private ?string $effectif;
    private ?string $codenaf;
    private ?string $fax;
    private ?string $siteweb;
    private int $siret;

    /**
     * Entreprise constructor.
     *
     * @param int $idutilisateur
     * @param string|null $statutjuridique
     * @param string|null $typestructure
     * @param string|null $effectif
     * @param string|null $codenaf
     * @param string|null $fax
     * @param string|null $siteweb
     * @param int $siret
     * @param string $emailutilisateur
     * @param string $nomutilisateur
     * @param string $numtelutilisateur
     */

    public function __construct(int $idutilisateur, ?string $statutjuridique, ?string $bio, ?string $typestructure, ?string $effectif, ?string $codenaf, ?string $fax, ?string $siteweb, int $siret, string $emailutilisateur, string $nomutilisateur, string $numtelutilisateur)
    {
        parent::__construct($idutilisateur, $emailutilisateur, $nomutilisateur, $numtelutilisateur, $bio);
        $this->idUtilisateur = $idutilisateur;
        $this->statutJuridique = $statutjuridique;
        $this->typeStructure = $typestructure;
        $this->effectif = $effectif;
        $this->codeNaf = $codenaf;
        $this->fax = $fax;
        $this->siteWeb = $siteweb;
        $this->siret = $siret;
    }

    public function getIdutilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idUtilisateur = $idutilisateur;
    }

    public function getStatutjuridique(): ?string
    {
        return $this->statutJuridique;
    }

    public function setStatutjuridique(?string $statutjuridique): void
    {
        $this->statutJuridique = $statutjuridique;
    }

    public function getTypestructure(): ?string
    {
        return $this->typeStructure;
    }

    public function setTypestructure(?string $typestructure): void
    {
        $this->typeStructure = $typestructure;
    }

    public function getEffectif(): ?string
    {
        return $this->effectif;
    }

    public function setEffectif(?string $effectif): void
    {
        $this->effectif = $effectif;
    }

    public function getCodenaf(): ?string
    {
        return $this->codeNaf;
    }

    public function setCodenaf(?string $codenaf): void
    {
        $this->codeNaf = $codenaf;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): void
    {
        $this->fax = $fax;
    }

    public function getSiteweb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteweb(?string $siteweb): void
    {
        $this->siteWeb = $siteweb;
    }

    public function getSiret(): int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): void
    {
        $this->siret = $siret;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }
}