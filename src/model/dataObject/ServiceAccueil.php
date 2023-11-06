<?php

namespace app\src\model\dataObject;

class ServiceAccueil extends AbstractDataObject
{
    private int $idService;
    private ?string $nomService;
    private ?string $adresse;
    private ?string $adresseCedex;
    private ?string $adresseResidence;
    private ?int $idVille;
    private ?int $idEntreprise;

    /**
     * ServiceAccueil constructor.
     *
     * @param int $idservice
     * @param string|null $nomservice
     * @param string|null $adresse
     * @param string|null $adressecedex
     * @param string|null $adresseresidence
     * @param int|null $idville
     * @param int|null $identreprise
     */
    public function __construct(int $idservice, ?string $nomservice, ?string $adresse, ?string $adressecedex, ?string $adresseresidence, ?int $idville, ?int $identreprise)
    {
        $this->idService = $idservice;
        $this->nomService = $nomservice;
        $this->adresse = $adresse;
        $this->adresseCedex = $adressecedex;
        $this->adresseResidence = $adresseresidence;
        $this->idVille = $idville;
        $this->idEntreprise = $identreprise;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        switch ($nomColonne) {
            case "idService":
                return $this->getIdService();
            case "nomService":
                return $this->getNomService();
            case "adresse":
                return $this->getAdresse();
            case "adresseCedex":
                return $this->getAdresseCedex();
            case "adresseResidence":
                return $this->getAdresseResidence();
            case "idVille":
                return $this->getIdVille();
            case "idEntreprise":
                return $this->getIdEntreprise();
            default:
                return "";
        }

    }

    public function getIdService(): int
    {
        return $this->idService;
    }

    public function setIdService(int $idService): void
    {
        $this->idService = $idService;
    }

    public function getNomService(): ?string
    {
        return $this->nomService;
    }

    public function setNomService(?string $nomService): void
    {
        $this->nomService = $nomService;
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
        return $this->adresseCedex;
    }

    public function setAdresseCedex(?string $adresseCedex): void
    {
        $this->adresseCedex = $adresseCedex;
    }

    public function getAdresseResidence(): ?string
    {
        return $this->adresseResidence;
    }

    public function setAdresseResidence(?string $adresseResidence): void
    {
        $this->adresseResidence = $adresseResidence;
    }

    public function getIdVille(): ?int
    {
        return $this->idVille;
    }

    public function setIdVille(?int $idVille): void
    {
        $this->idVille = $idVille;
    }

    public function getIdEntreprise(): ?int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(?int $idEntreprise): void
    {
        $this->idEntreprise = $idEntreprise;
    }
}