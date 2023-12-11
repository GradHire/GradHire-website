<?php

namespace app\src\model\dataObject;

use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;

class Entreprise extends Utilisateur
{
    private int $idutilisateur;
    private ?string $statusjuridique;
    private ?string $typestructure;
    private ?string $effectif;
    private ?string $codenaf;
    private ?string $fax;
    private ?string $siteweb;
    private string $siret;
    private ?string $adresse;
    private ?string $codepostal;
    private ?string $nomville;
    private ?string $pays;

    public function __construct(
        array $attributes
    )
    {
        parent::__construct($attributes);
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getStatutjuridique(): ?string
    {
        return $this->statusjuridique;
    }

    public function setStatutjuridique(?string $statutjuridique): void
    {
        $this->statusjuridique = $statutjuridique;
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(int $siret): void
    {
        $this->siret = $siret;
    }

    public function getCodePostal(): ?string
    {
        return $this->codepostal;
    }

    public function getVille(): ?string
    {
        return $this->nomville;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getRole(): ?string
    {
        return "Entreprise";
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->strval($$nomColonne);
    }

}
