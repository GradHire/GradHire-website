<?php

namespace app\src\model\dataObject;

class Supervise extends AbstractDataObject
{
    private int $idetudiant;
    private int $idutilisateur;
    private int $idtuteurentreprise;
    private int $idoffre;
    private string $statut;

    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getIdetudiant(): int
    {
        return $this->idetudiant;
    }

    public function setIdetudiant(int $idetudiant): void
    {
        $this->idetudiant = $idetudiant;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getIdtuteurentreprise(): int
    {
        return $this->idtuteurentreprise;
    }

    public function setIdtuteurentreprise(int $idtuteurentreprise): void
    {
        $this->idtuteurentreprise = $idtuteurentreprise;
    }

    public function getIdoffre(): int
    {
        return $this->idoffre;
    }

    public function setIdoffre(int $idoffre): void
    {
        $this->idoffre = $idoffre;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }


    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}
