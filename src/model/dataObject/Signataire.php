<?php

namespace app\src\model\dataObject;

class Signataire extends AbstractDataObject
{
    private int $idSignataire;
    private ?string $nomSignataire;
    private ?string $prenomSignataire;
    private ?string $fonctionSignataire;
    private ?string $mailSignataire;
    private ?string $idEntreprise;

    /**
     * Signataire constructor.
     *
     * @param int $idsignataire
     * @param string|null $nomsignataire
     * @param string|null $prenomsignataire
     * @param string|null $fonctionsignataire
     * @param string|null $mailsignataire
     * @param string|null $identreprise
     */
    public function __construct(int $idsignataire, ?string $nomsignataire, ?string $prenomsignataire, ?string $fonctionsignataire, ?string $mailsignataire, ?string $identreprise)
    {
        $this->idSignataire = $idsignataire;
        $this->nomSignataire = $nomsignataire;
        $this->prenomSignataire = $prenomsignataire;
        $this->fonctionSignataire = $fonctionsignataire;
        $this->mailSignataire = $mailsignataire;
        $this->idEntreprise = $identreprise;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        switch ($nomColonne) {
            case "idSignataire":
                return $this->getIdSignataire();
            case "nomSignataire":
                return $this->getNomSignataire();
            case "prenomSignataire":
                return $this->getPrenomSignataire();
            case "fonctionSignataire":
                return $this->getFonctionSignataire();
            case "mailSignataire":
                return $this->getMailSignataire();
            case "idEntreprise":
                return $this->getIdEntreprise();
            default:
                return "";
        }
    }

    public function getIdSignataire(): int
    {
        return $this->idSignataire;
    }

    public function setIdSignataire(int $idSignataire): void
    {
        $this->idSignataire = $idSignataire;
    }

    public function getNomSignataire(): ?string
    {
        return $this->nomSignataire;
    }

    public function setNomSignataire(?string $nomSignataire): void
    {
        $this->nomSignataire = $nomSignataire;
    }

    public function getPrenomSignataire(): ?string
    {
        return $this->prenomSignataire;
    }

    public function setPrenomSignataire(?string $prenomSignataire): void
    {
        $this->prenomSignataire = $prenomSignataire;
    }

    public function getFonctionSignataire(): ?string
    {
        return $this->fonctionSignataire;
    }

    public function setFonctionSignataire(?string $fonctionSignataire): void
    {
        $this->fonctionSignataire = $fonctionSignataire;
    }

    public function getMailSignataire(): ?string
    {
        return $this->mailSignataire;
    }

    public function setMailSignataire(?string $mailSignataire): void
    {
        $this->mailSignataire = $mailSignataire;
    }

    public function getIdEntreprise(): ?string
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(?string $idEntreprise): void
    {
        $this->idEntreprise = $idEntreprise;
    }
}