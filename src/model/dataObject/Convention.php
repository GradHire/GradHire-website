<?php
namespace app\src\model\dataObject;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\repository\ConventionRepository;

class Convention extends AbstractDataObject
{
    private int $numConvention;
    private string $origineConvention;
    private int $conventionValide;
    private int $convetionValideePedagogiquement;
    private string $dateModification;
    private string $dateCreation;
    private int $idSignataire;
    private ?int $idInterruption;
    private int $idUtilisateur;
    private int $idOffre;
    private ?string $Commentaire;

    public function __construct(int $numConvention, string $origineCovention, int $conventionValide, int $convetionValideePedagogiquement, string $dateModification, string $dateCreation, int $idSignataire, ?int $idInterruption, int $idUtilisateur, int $idOffre, ?string $Commentaire)
    {
        $this->numConvention = $numConvention;
        $this->origineConvention = $origineCovention;
        $this->conventionValide = $conventionValide;
        $this->convetionValideePedagogiquement = $convetionValideePedagogiquement;
        $this->dateModification = $dateModification;
        $this->dateCreation = $dateCreation;
        $this->idSignataire = $idSignataire;
        $this->idInterruption = $idInterruption;
        $this->idUtilisateur = $idUtilisateur;
        $this->idOffre = $idOffre;
        $this->Commentaire = $Commentaire;
    }

    public function getNumConvention(): int
    {
        return $this->numConvention;
    }

    public function setNumConvention(int $numConvention): void
    {
        $this->numConvention = $numConvention;
    }

    public function getOrigineConvention(): string
    {
        return $this->origineConvention;
    }

    public function setOrigineConvention(string $origineCovention): void
    {
        $this->origineCovention = $origineCovention;
    }

    public function getConventionValide(): int
    {
        return $this->conventionValide;
    }

    public function setConventionValide(int $conventionValide): void
    {
        $this->conventionValide = $conventionValide;
    }

    public function getConvetionValideePedagogiquement(): int
    {
        return $this->convetionValideePedagogiquement;
    }

    public function setConvetionValideePedagogiquement(int $convetionValideePedagogiquement): void
    {
        $this->convetionValideePedagogiquement = $convetionValideePedagogiquement;
    }

    public function getDateModification(): string
    {
        return $this->dateModification;
    }

    public function setDateModification(string $dateModification): void
    {
        $this->dateModification = $dateModification;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getIdSignataire(): int
    {
        return $this->idSignataire;
    }

    public function setIdSignataire(int $idSignataire): void
    {
        $this->idSignataire = $idSignataire;
    }



    public function getIdInterruption(): ?int
    {
        return $this->idInterruption;
    }

    public function setIdInterruption(?int $idInterruption): void
    {
        $this->idInterruption = $idInterruption;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getIdOffre(): int
    {
        return $this->idOffre;
    }

    public function setIdOffre(int $idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(?string $Commentaire): void
    {
        $this->Commentaire = $Commentaire;
    }

    /**
     * @throws ServerErrorException
     */
    public function validerPedagogiquement(mixed $id)
    {
        (new ConventionRepository())->validerPedagogiquement($id);
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidatePedagogiquement(mixed $id)
    {
        (new ConventionRepository())->unvalidatePedagogiquement($id);
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidate(mixed $id)
    {
        (new ConventionRepository())->unvalidate($id);
    }

    /**
     * @throws ServerErrorException
     */
    public function valider(mixed $id)
    {
        (new ConventionRepository())->valider($id);
    }


    protected function getValueColonne(string $nomColonne): string
    {
        switch ($nomColonne) {
            case "numConvention":
                return $this->numConvention;
            case "origineCovention":
                return $this->origineCovention;
            case "conventionValide":
                return $this->conventionValide;
            case "convetionValideePedagogiquement":
                return $this->convetionValideePedagogiquement;
            case "dateModification":
                return $this->dateModification;
            case "dateCreation":
                return $this->dateCreation;
            case "idSignataire":
                return $this->idCreation;
            case "idInterruption":
                return $this->idInterruption;
            case "idUtilisateur":
                return $this->idUtilisateur;
            case "idOffre":
                return $this->idOffre;
            case "Commentaire":
                return $this->Commentaire;
            default:
                return "";
        }
    }
}