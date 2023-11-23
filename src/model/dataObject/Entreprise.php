<?php

namespace app\src\model\dataObject;

use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;

class Entreprise extends Utilisateur
{
    private int $idUtilisateur;
    private ?string $statutJuridique;
    private ?string $typeStructure;
    private ?string $effectif;
    private ?string $codeNaf;
    private ?string $fax;
    private ?string $siteWeb;
    private int $siret;
    private ?string $adresse;
    private ?string $codePostal;
    private ?string $ville;
    private ?string $pays;

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
     * @param string|null $adresse
     * @param string|null $codePostal
     * @param string|null $ville
     * @param string|null $pays
     */

    public function __construct(int $idutilisateur, ?string $statutjuridique, ?string $bio, ?string $typestructure, ?string $effectif, ?string $codenaf, ?string $fax, ?string $siteweb, int $siret, string $emailutilisateur, string $nomutilisateur, string $numtelutilisateur, ?string $adresse, ?string $codePostal, ?string $ville, ?string $pays)
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
        $this->adresse = $adresse;
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->pays = $pays;
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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function getPays(): ?string
    {
        return $this->pays;
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