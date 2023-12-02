<?php

namespace app\src\model\dataObject;

use app\src\model\repository\ServiceAccueilRepository;

class ServiceAccueil extends AbstractDataObject
{
    private int $idservice;
    private ?string $nomservice;
    private ?string $adresse;
    private ?string $adressecedex;
    private ?string $adresseresidence;
    private ?int $idville;
    private ?int $identreprise;


    public function __construct(
        array $attributes
    )
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getCommune(): ?string
    {
        $servaccueil = new ServiceAccueilRepository();
        return $servaccueil->getCommune($this->identreprise, $this->nomservice);
    }

    public function getCodePostal(): ?string
    {
        $servaccueil = new ServiceAccueilRepository();
        return $servaccueil->getCodePostal($this->identreprise, $this->nomservice);
    }

    public function getPays(): ?string
    {
        $servaccueil = new ServiceAccueilRepository();
        return $servaccueil->getPays($this->identreprise, $this->nomservice);
    }

    public function getIdService(): int
    {
        return $this->idservice;
    }

    public function setIdService(int $idService): void
    {
        $this->idservice = $idService;
    }

    public function getNomService(): ?string
    {
        return $this->nomservice;
    }

    public function setNomService(?string $nomService): void
    {
        $this->nomservice = $nomService;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getAdresseCedex(): ?string
    {
        return $this->adressecedex;
    }

    public function setAdresseCedex(?string $adresseCedex): void
    {
        $this->adressecedex = $adresseCedex;
    }

    public function getAdresseResidence(): ?string
    {
        return $this->adresseresidence;
    }

    public function setAdresseResidence(?string $adresseResidence): void
    {
        $this->adresseresidence = $adresseResidence;
    }

    public function getIdVille(): ?int
    {
        return $this->idville;
    }

    public function setIdVille(?int $idVille): void
    {
        $this->idville = $idVille;
    }

    public function getIdEntreprise(): ?int
    {
        return $this->identreprise;
    }

    public function setIdEntreprise(?int $idEntreprise): void
    {
        $this->identreprise = $idEntreprise;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}