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
    private ?int $nbjourtravailhebdo;
    private ?float $nbheuretravailhebdo;
    private ?float $gratification;
    private ?string $avantagesnature;
    private ?string $datedebut;
    private ?string $datefin;
    private ?string $datecreation;
    private ?string $statut;
    private ?string $pourvue;
    private ?string $anneevisee;
    private ?string $annee;
    private int $idutilisateur;
    private ?string $description;

    private ?string $adresse;

//    private ?int $alternance;
    public function __construct
    (
        array $attributes
    )
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        $this->adresse = (new EntrepriseRepository([]))->getByIdFull($this->idutilisateur)->getAdresse();
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
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
        return $this->nbjourtravailhebdo;
    }

    public function setNbJourTravailHebdo(?int $nbJourTravailHebdo): void
    {
        $this->nbjourtravailhebdo = $nbJourTravailHebdo;
    }

    public function getNbHeureTravailHebdo(): ?float
    {
        return $this->nbheuretravailhebdo;
    }

    public function setNbHeureTravailHebdo(?float $nbHeureTravailHebdo): void
    {
        $this->nbheuretravailhebdo = $nbHeureTravailHebdo;
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
        return $this->avantagesnature;
    }

    public function setAvantageNature(?string $avantageNature): void
    {
        $this->avantagesnature = $avantageNature;
    }

    public function getDateDebut(): ?string
    {
        return $this->datedebut;
    }

    public function setDateDebut(?string $dateDebut): void
    {
        $this->datedebut = $dateDebut;
    }

    public function getDateFin(): ?string
    {
        return $this->datefin;
    }

    public function setDateFin(?string $dateFin): void
    {
        $this->datefin = $dateFin;
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
        return $this->anneevisee;
    }

    public function setAnneeVisee(?string $anneeVisee): void
    {
        $this->anneevisee = $anneeVisee;
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
        return $this->datecreation;
    }

    public function setDateCreation(?string $dateCreation): void
    {
        $this->datecreation = $dateCreation;
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

    public function getNomEntreprise(): string
    {
        return (new EntrepriseRepository([]))->getByIdFull($this->idutilisateur)->getNom();
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->strval($$nomColonne);
    }

}