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
    private int $validee;

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
     * @param int $validee
     */

    public function __construct(int $idutilisateur, ?string $statutjuridique, ?string $typestructure, ?string $effectif, ?string $codenaf, ?string $fax, ?string $siteweb, int $siret, int $validee, string $emailutilisateur, string $nomutilisateur, string $numtelutilisateur)
    {
        parent::__construct($idutilisateur, $emailutilisateur, $nomutilisateur, $numtelutilisateur);
        $this->idutilisateur = $idutilisateur;
        $this->statutjuridique = $statutjuridique;
        $this->typestructure = $typestructure;
        $this->effectif = $effectif;
        $this->codenaf = $codenaf;
        $this->fax = $fax;
        $this->siteweb = $siteweb;
        $this->siret = $siret;
        $this->validee = $validee;
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

    public function getStatutjuridique(): ?string
    {
        return $this->statutjuridique;
    }

    public function setStatutjuridique(?string $statutjuridique): void
    {
        $this->statutjuridique = $statutjuridique;
    }

    public function getTypestructure(): ?string
    {
        return $this->typestructure;
    }

    public function setTypestructure(?string $typestructure): void
    {
        $this->typestructure = $typestructure;
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
        return $this->codenaf;
    }

    public function setCodenaf(?string $codenaf): void
    {
        $this->codenaf = $codenaf;
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
        return $this->siteweb;
    }

    public function setSiteweb(?string $siteweb): void
    {
        $this->siteweb = $siteweb;
    }

    public function getSiret(): int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): void
    {
        $this->siret = $siret;
    }

    public function getValidee(): int
    {
        return $this->validee;
    }

    public function setValidee(int $validee): void
    {
        $this->validee = $validee;
    }
}