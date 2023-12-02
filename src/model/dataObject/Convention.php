<?php

namespace app\src\model\dataObject;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\repository\ConventionRepository;

class Convention extends AbstractDataObject
{
    private int $numconvention;
    private string $origineconvention;
    private int $conventionvalidee;
    private int $conventionvalideepedagogiquement;
    private string $datemodification;
    private string $datecreation;
    private int $idsignataire;
    private ?int $idinterruption;
    private int $idutilisateur;
    private int $idoffre;
    private ?string $commentaire;

    public function __construct(array $array)
    {
        foreach ($array as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getNumconvention(): int
    {
        return $this->numconvention;
    }

    public function setNumconvention(int $numconvention): void
    {
        $this->numconvention = $numconvention;
    }

    public function getOrigineconvention(): string
    {
        return $this->origineconvention;
    }

    public function setOrigineconvention(string $origineconvention): void
    {
        $this->origineconvention = $origineconvention;
    }

    public function getConventionvalidee(): int
    {
        return $this->conventionvalidee;
    }

    public function setConventionvalidee(int $conventionvalidee): void
    {
        $this->conventionvalidee = $conventionvalidee;
    }


    public function getConvetionvalideepedagogiquement(): int
    {
        return $this->conventionvalideepedagogiquement;
    }

    public function setConvetionvalideepedagogiquement(int $convetionvalideepedagogiquement): void
    {
        $this->conventionvalideepedagogiquement = $convetionvalideepedagogiquement;
    }

    public function getDatemodification(): string
    {
        return $this->datemodification;
    }

    public function setDatemodification(string $datemodification): void
    {
        $this->datemodification = $datemodification;
    }

    public function getDatecreation(): string
    {
        return $this->datecreation;
    }

    public function setDatecreation(string $datecreation): void
    {
        $this->datecreation = $datecreation;
    }

    public function getIdsignataire(): int
    {
        return $this->idsignataire;
    }

    public function setIdsignataire(int $idsignataire): void
    {
        $this->idsignataire = $idsignataire;
    }

    public function getIdinterruption(): ?int
    {
        return $this->idinterruption;
    }

    public function setIdinterruption(?int $idinterruption): void
    {
        $this->idinterruption = $idinterruption;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getIdoffre(): int
    {
        return $this->idoffre;
    }

    public function setIdoffre(int $idoffre): void
    {
        $this->idoffre = $idoffre;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}