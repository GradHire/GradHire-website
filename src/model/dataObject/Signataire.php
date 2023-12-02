<?php

namespace app\src\model\dataObject;

class Signataire extends AbstractDataObject
{
    private int $idsignataire;
    private ?string $nomsignataire;
    private ?string $prenomsignataire;
    private ?string $fonctionsignataire;
    private ?string $mailsignataire;
    private ?string $identreprise;

    public function __construct(array $attributes
    )
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getIdSignataire(): int
    {
        return $this->idsignataire;
    }

    public function setIdSignataire(int $idSignataire): void
    {
        $this->idsignataire = $idSignataire;
    }

    public function getNomSignataire(): ?string
    {
        return $this->nomsignataire;
    }

    public function setNomSignataire(?string $nomSignataire): void
    {
        $this->nomsignataire = $nomSignataire;
    }

    public function getPrenomSignataire(): ?string
    {
        return $this->prenomsignataire;
    }

    public function setPrenomSignataire(?string $prenomSignataire): void
    {
        $this->prenomsignataire = $prenomSignataire;
    }

    public function getFonctionSignataire(): ?string
    {
        return $this->fonctionsignataire;
    }

    public function setFonctionSignataire(?string $fonctionSignataire): void
    {
        $this->fonctionsignataire = $fonctionSignataire;
    }

    public function getMailSignataire(): ?string
    {
        return $this->mailsignataire;
    }

    public function setMailSignataire(?string $mailSignataire): void
    {
        $this->mailsignataire = $mailSignataire;
    }

    public function getIdEntreprise(): ?string
    {
        return $this->identreprise;
    }

    public function setIdEntreprise(?string $idEntreprise): void
    {
        $this->identreprise = $idEntreprise;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}