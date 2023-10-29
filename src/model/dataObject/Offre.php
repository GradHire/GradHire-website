<?php

namespace app\src\model\dataObject;

use app\src\core\exception\ServerErrorException;
use app\src\model\repository\OffresRepository;

class Offre extends AbstractDataObject
{
    private ?int $idoffre;
    private ?string $duree;
    private ?string $thematique;
    private ?string $sujet;
    private ?int $nbjourtravailhebdo;
    private ?float $nbHeureTravailHebdo;
    private ?float $gratification;
    private ?string $unitegratification;
    private ?string $avantageNature;
    private ?string $dateDebut;
    private ?string $dateFin;
    private ?string $statut;
    private ?string $anneeVisee;
    private string $idAnnee;
    private int $idutilisateur;
    private ?string $description;
    private string $datecreation;
    private ?string $nomutilisateur;

//    private ?int $alternance;
    public function __construct
    (
        $idOffre,
        $duree,
        $thematique,
        $sujet,
        $nbJourTravailHebdo,
        $nbHeureTravailHebdo,
        $gratification,
        $unitegratification,
        $avantageNature,
        $dateDebut,
        $dateFin,
        $statut,
        $anneeVisee,
        $idAnnee,
        $idUtilisateur,
        $description,
        $datecreation,
        $nomutilisateur
//        $alternance
    )
    {
        $this->idoffre = $idOffre;
        $this->duree = $duree;
        $this->thematique = $thematique;
        $this->sujet = $sujet;
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
        $this->gratification = $gratification;
        $this->uniteGratification = $unitegratification;
        $this->avantageNature = $avantageNature;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->statut = $statut;
        $this->anneeVisee = $anneeVisee;
        $this->idAnnee = $idAnnee;
        $this->idUtilisateur = $idUtilisateur;
        $this->description = $description;
        $this->datecreation = $datecreation;
        $this->nomUtilisateur = $nomutilisateur;
    }

    public function getId(): ?int
    {
        return $this->idoffre;
    }

    public function __toString(): string
    {
        return "Offre : " . $this->getSujet() . " " . $this->getThematique() . " " . $this->getDuree() . " " . $this->getNbjourtravailhebdo() . " " . $this->getNbHeureTravailHebdo() . " " . $this->getGratification() . " " . $this->getUnitegratification() . " " . $this->getAvantageNature() . " " . $this->getDateDebut() . " " . $this->getDateFin() . " " . $this->getStatut() . " " . $this->getAnneeVisee() . " " . $this->getIdAnnee() . " " . $this->getIdutilisateur() . " " . $this->getDescription();
    }

    /**
     * @return string
     */
    public function getSujet(): string
    {
        return $this->sujet;
    }

    /**
     * @param string $sujet
     */
    public function setSujet(string $sujet): void
    {
        $this->sujet = $sujet;
    }

    /**
     * @return string
     */
    public function getThematique(): ?string
    {
        return $this->thematique;
    }

    /**
     * @param string $thematique
     */
    public function setThematique(string $thematique): void
    {
        $this->thematique = $thematique;
    }

    /**
     * @return string
     */
    public function getDuree(): ?string
    {
        return $this->duree;
    }

    /**
     * @param string $duree
     */
    public function setDuree(string $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return int
     */
    public function getNbjourtravailhebdo(): ?int
    {
        return $this->nbJourTravailHebdo;
    }

    /**
     * @param int $nbjourtravailhebdo
     */
    public function setNbjourtravailhebdo(int $nbjourtravailhebdo): void
    {
        $this->nbJourTravailHebdo = $nbjourtravailhebdo;
    }

    /**
     * @return float
     */
    public function getNbHeureTravailHebdo(): ?float
    {
        return $this->nbHeureTravailHebdo;
    }

    /**
     * @param float $nbHeureTravailHebdo
     */
    public function setNbHeureTravailHebdo(float $nbHeureTravailHebdo): void
    {
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
    }

    /**
     * @return float
     */
    public function getGratification(): ?float
    {
        return $this->gratification;
    }

    /**
     * @param float $gratification
     */
    public function setGratification(float $gratification): void
    {
        $this->gratification = $gratification;
    }

    /**
     * @return string
     */
    public function getUnitegratification(): ?string
    {
        return $this->uniteGratification;
    }

    /**
     * @param string $unitegratification
     */
    public function setUnitegratification(string $unitegratification): void
    {
        $this->uniteGratification = $unitegratification;
    }

    /**
     * @return string
     */
    public function getAvantageNature(): ?string
    {
        return $this->avantageNature;
    }

    /**
     * @param string $avantageNature
     */
    public function setAvantageNature(string $avantageNature): void
    {
        $this->avantageNature = $avantageNature;
    }

    /**
     * @return string
     */
    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    /**
     * @param string $dateDebut
     */
    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return string
     */
    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    /**
     * @param string $dateFin
     */
    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @return string
     */
    public function getAnneeVisee(): ?string
    {
        return $this->anneeVisee;
    }

    /**
     * @param string $anneeVisee
     */
    public function setAnneeVisee(string $anneeVisee): void
    {
        $this->anneeVisee = $anneeVisee;
    }

    /**
     * @return string
     */
    public function getIdAnnee(): string
    {
        return $this->idAnnee;
    }

    /**
     * @return int
     */
    public function getIdutilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function getDatecreation(): string
    {
        return $this->datecreation;
    }

    public function getNomutilisateur()
    {
        return $this->nomUtilisateur;
    }

    public function getPicture(): string
    {
        if (file_exists("./pictures/" . $this->idoffre . ".jpg")) return "/pictures/" . $this->idoffre . ".jpg";
        return "https://as2.ftcdn.net/v2/jpg/00/64/67/63/1000_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg";
    }

    /**
     * @throws ServerErrorException
     */
    public function getUserPostuled(): bool
    {
        return (new OffresRepository())->checkIfUserPostuled($this);
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

}