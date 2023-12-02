<?php

namespace app\src\model\dataObject;

use app\src\model\dataObject\Utilisateur;

class Etudiant extends Utilisateur
{
    private static int $convId = 0;
    private int $idutilisateur;
    private string $prenom;
    private ?string $loginLDAP;
    private ?string $numetudiant;
    private ?string $adresse;
    private ?string $dateNaissance;
    private ?string $emailperso;
    private ?string $codeSexe;
    private ?int $idgroupe;
    private ?string $nomville;
    private ?int $codepostal;
    private ?string $pays;
    private ?bool $archiver;
    private ?int $annee;

    public function __construct(
        array $attributes
    )
    {
        parent::__construct($attributes);
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public static function getConvId(): int
    {
        return self::$convId;
    }

    public static function setConvId(int $convId): void
    {
        self::$convId = $convId;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idutilisateur = $idUtilisateur;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getLoginLDAP(): ?string
    {
        return $this->loginLDAP;
    }

    public function setLoginLDAP(string $loginLDAP): void
    {
        $this->loginLDAP = $loginLDAP;
    }

    public function getNumEtudiant(): ?string
    {
        return $this->numetudiant;
    }

    public function setNumEtudiant(?string $numEtudiant): void
    {
        $this->nueEtudiant = $numEtudiant;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?string $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    public function getEmailPerso(): ?string
    {
        return $this->emailperso;
    }

    public function setEmailPerso(?string $emailPerso): void
    {
        $this->emailperso = $emailPerso;
    }

    public function getCodeSexe(): ?string
    {
        return $this->codeSexe;
    }

    public function setCodeSexe(?string $codeSexe): void
    {
        $this->codeSexe = $codeSexe;
    }

    public function getIdgroupe(): ?int
    {
        return $this->idgroupe;
    }

    public function setIdgroupe(?int $idgroupe): void
    {
        $this->idgroupe = $idgroupe;
    }

    public function getNomVille(): ?string
    {
        return $this->nomville;
    }

    public function setNomVille(?string $nomVille): void
    {
        $this->nomville = $nomVille;
    }

    public function getCodePostal(): ?int
    {
        return $this->codepostal;
    }

    public function setCodePostal(?int $codePostal): void
    {
        $this->codepostal = $codePostal;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): void
    {
        $this->pays = $pays;
    }

    public function getArchiver(): ?bool
    {
        return $this->archiver;
    }

    public function setArchiver(?bool $archiver): void
    {
        $this->archiver = $archiver;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }


    protected function getValueColonne(string $nomColonne): string
    {
        return $this->strval($$nomColonne);
    }


}