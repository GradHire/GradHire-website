<?php

namespace app\src\model\dataObject;

use app\src\core\exception\ServerErrorException;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;

class Offre extends AbstractDataObject
{
    private ?int $idoffre;
    private ?string $duree;
    private ?string $thematique;
    private ?string $sujet;
    private ?int $nbJourTravailHebdo;
    private ?float $nbHeureTravailHebdo;
    private ?float $gratification;
    private ?string $avantageNature;
    private ?string $dateDebut;
    private ?string $dateFin;
    private ?string $dateCreation;
    private ?string $statut;
    private ?string $pourvue;
    private ?string $anneeVisee;
    private ?string $annee;
    private int $idutilisateur;
    private ?string $description;


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
        $avantageNature,
        $dateDebut,
        $dateFin,
        $statut,
        $pourvue,
        $anneeVisee,
        $annee,
        $idUtilisateur,
        $dateCreation,
        $description
    )
    {
        $this->idoffre = $idOffre;
        $this->duree = $duree;
        $this->thematique = $thematique;
        $this->sujet = $sujet;
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
        $this->gratification = $gratification;
        $this->avantageNature = $avantageNature;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->statut = $statut;
        $this->pourvue = $pourvue;
        $this->anneeVisee = $anneeVisee;
        $this->annee = $annee;
        $this->idutilisateur = $idUtilisateur;
        $this->dateCreation = $dateCreation;
        $this->description = $description;
    }

    public function __toString(): string
    {
        return "Offre : " .
            $this->idoffre . " " .
            $this->duree . " " .
            $this->thematique . " " .
            $this->sujet . " " .
            $this->nbJourTravailHebdo . " " .
            $this->nbHeureTravailHebdo . " " .
            $this->gratification . " " .
            $this->avantageNature . " " .
            $this->dateDebut . " " .
            $this->dateFin . " " .
            $this->statut . " " .
            $this->pourvue . " " .
            $this->anneeVisee . " " .
            $this->annee . " " .
            $this->idutilisateur . " ";
    }

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function setIdoffre(?int $idoffre): void
    {
        $this->idoffre = $idoffre;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): void
    {
        $this->duree = $duree;
    }

    public function getThematique(): ?string
    {
        return $this->thematique;
    }

    public function setThematique(?string $thematique): void
    {
        $this->thematique = $thematique;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): void
    {
        $this->sujet = $sujet;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getNbJourTravailHebdo(): ?int
    {
        return $this->nbJourTravailHebdo;
    }

    public function setNbJourTravailHebdo(?int $nbJourTravailHebdo): void
    {
        $this->nbJourTravailHebdo = $nbJourTravailHebdo;
    }

    public function getNbHeureTravailHebdo(): ?float
    {
        return $this->nbHeureTravailHebdo;
    }

    public function setNbHeureTravailHebdo(?float $nbHeureTravailHebdo): void
    {
        $this->nbHeureTravailHebdo = $nbHeureTravailHebdo;
    }

    public function getGratification(): ?float
    {
        return $this->gratification;
    }

    public function setGratification(?float $gratification): void
    {
        $this->gratification = $gratification;
    }

    public function getAvantageNature(): ?string
    {
        return $this->avantageNature;
    }

    public function setAvantageNature(?string $avantageNature): void
    {
        $this->avantageNature = $avantageNature;
    }

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(?string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    public function getPourvue(): ?string
    {
        return $this->pourvue;
    }

    public function setPourvue(?string $pourvue): void
    {
        $this->pourvue = $pourvue;
    }

    public function getAnneeVisee(): ?string
    {
        return $this->anneeVisee;
    }

    public function setAnneeVisee(?string $anneeVisee): void
    {
        $this->anneeVisee = $anneeVisee;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): void
    {
        $this->annee = $annee;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
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

    public function getNomEntreprise(): string
    {
        return (new EntrepriseRepository([]))->getByIdFull($this->idutilisateur)->getNomutilisateur();
    }

}