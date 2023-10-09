<?php

namespace app\src\model\dataObject;

class Offre extends AbstractDataObject
{
    private ?int $idOffre;
    private ?string $duree;
    private string $thematique;
    private string $sujet;
    private ?int $nbJourTravailHebdo;
    private ?float $nbHeureTravailHebdo;
    private ?float $gratification;
    private ?string $unitegratification;
    private ?string $avantageNature;
    private string $dateDebut;
    private ?string $dateFin;
    private ?string $statut;
    private ?string $anneeVisee;
    private string $idAnnee;
    private int $idUtilisateur;
    private string $description;
    private string $datecreation;

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
        $datecreation
//        $alternance
    )
    {
        $this->idOffre = $idOffre;
        $this->duree = $duree;
        $this->thematique = $thematique;
        $this->sujet = $sujet;
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
        $this->gratification = $gratification;
        $this->unitegratification = $unitegratification;
        $this->avantageNature = $avantageNature;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->statut = $statut;
        $this->anneeVisee = $anneeVisee;
        $this->idAnnee = $idAnnee;
        $this->idUtilisateur = $idUtilisateur;
        $this->description = $description;
        $this->datecreation = $datecreation;
    }

    public function __toString(): string
    {
        return "Offre : " . $this->getSujet() . " " . $this->getThematique() . " " . $this->getDuree() . " " . $this->getNbJourTravailHebdo() . " " . $this->getNbHeureTravailHebdo() . " " . $this->getGratification() . " " . $this->getUnitegratification() . " " . $this->getAvantageNature() . " " . $this->getDateDebut() . " " . $this->getDateFin() . " " . $this->getStatut() . " " . $this->getAnneeVisee() . " " . $this->getIdAnnee() . " " . $this->getIdUtilisateur() . " " . $this->getDescription();
    }

    protected function getValueColonne(string $nomColonne): string
    {
        switch ($nomColonne) {
            case "idoffre":
                return $this->getIdOffre();
            case "duree":
                return $this->getDuree();
            case "thematique":
                return $this->getThematique();
            case "sujet":
                return $this->getSujet();
            case "nbjourtravailHebdo":
                return $this->getNbJourTravailHebdo();
            case "nbheuretravailHebdo":
                return $this->getNbHeureTravailHebdo();
            case "gratification":
                return $this->getGratification();
            case "unitegratification":
                return $this->getUnitegratification();
            case "avantageNature":
                return $this->getAvantageNature();
            case "datedebut":
                return $this->getDateDebut();
            case "datefin":
                return $this->getDateFin();
            case "statut":
                return $this->getStatut();
            case "anneevisee":
                return $this->getAnneeVisee();
            case "idannee":
                return $this->getIdAnnee();
            case "idutilisateur":
                return $this->getIdUtilisateur();
            case "description":
                return $this->getDescription();
                case "datecreation":
                return $this->getDatecreation();
//            case "alternance":
//                return $this->getAlternance();
            default:
                return "";
        }
    }

//    public function getAlternance(): ?int
//    {
//        return $this->alternance;
//    }

    /**
     * @return int
     */
    public function getIdOffre(): ?int
    {
        return $this->idOffre;
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
     * @return string
     */
    public function getThematique(): string
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
     * @return int
     */
    public function getNbJourTravailHebdo(): ?int
    {
        return $this->nbJourTravailHebdo;
    }

    /**
     * @param int $nbJourTravailHebdo
     */
    public function setNbJourTravailHebdo(int $nbJourTravailHebdo): void
    {
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
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
        return $this->unitegratification;
    }

    /**
     * @param string $unitegratification
     */
    public function setUnitegratification(string $unitegratification): void
    {
        $this->unitegratification = $unitegratification;
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
    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

//    public function getAlternance(): ?int
//    {
//        return $this->alternance;
//    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDatecreation(): string
    {
        return $this->datecreation;
    }





}