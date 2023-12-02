<?php

namespace app\src\model\dataObject;

class SimulationPstage extends AbstractDataObject
{
    private int $idsimulation;
    private ?string $nomfichier;
    private ?string $statut;
    private ?string $idetudiant;

    public function __construct(array $attributes
    )
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getIdSimulation(): int
    {
        return $this->idsimulation;
    }

    public function setIdSimulation(int $idSimulation): void
    {
        $this->idsimulation = $idSimulation;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomfichier;
    }

    public function setNomFichier(?string $nomFichier): void
    {
        $this->nomfichier = $nomFichier;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    public function getIdEtudiant(): ?string
    {
        return $this->idetudiant;
    }

    public function setIdEtudiant(?string $idEtudiant): void
    {
        $this->idetudiant = $idEtudiant;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->strval($$nomColonne);
    }

}