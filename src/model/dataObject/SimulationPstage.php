<?php

namespace app\src\model\dataObject;

class SimulationPstage extends AbstractDataObject
{
    private int $idSimulation;
    private ?string $nomFichier;
    private ?string $statut;
    private ?string $idEtudiant;

    /**
     * SimulationPstage constructor.
     *
     * @param int $idsimulation
     * @param string|null $nomfichier
     * @param string|null $statut
     * @param string|null $idetudiant
     */
    public function __construct(int $idsimulation, ?string $nomfichier, ?string $statut, ?string $idetudiant)
    {
        $this->idSimulation = $idsimulation;
        $this->nomFichier = $nomfichier;
        $this->statut = $statut;
        $this->idEtudiant = $idetudiant;
    }


    protected function getValueColonne(string $nomColonne): string
    {
        switch ($nomColonne) {
            case "idSimulation":
                return $this->getIdSimulation();
            case "nomFichier":
                return $this->getNomFichier();
            case "statut":
                return $this->getStatut();
            case "idEtudiant":
                return $this->getIdEtudiant();
            default:
                return "";
        }
    }

    public function getIdSimulation(): int
    {
        return $this->idSimulation;
    }

    public function setIdSimulation(int $idSimulation): void
    {
        $this->idSimulation = $idSimulation;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): void
    {
        $this->nomFichier = $nomFichier;
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
        return $this->idEtudiant;
    }

    public function setIdEtudiant(?string $idEtudiant): void
    {
        $this->idEtudiant = $idEtudiant;
    }

}