<?php

namespace app\src\model\dataObject;

use app\src\model\dataObject\Utilisateur;

class Etudiant extends Utilisateur
{
    private static int $convId = 0;
    private int $idUtilisateur;
    private string $prenom;
    private ?string $loginLDAP;
    private ?string $numEtudiant;
    private ?string $adresse;
    private ?string $dateNaissance;
    private ?string $emailPerso;
    private ?string $codeSexe;
    private ?int $idgroupe;
    private ?string $nomVille;
    private ?int $codePostal;
    private ?string $pays;
    private ?bool $archiver;
    private ?int $annee;

    public function __construct(
        int     $idUtilisateur,
        string  $email,
        string  $nom,
        ?string $numTelephone,
        ?string $bio,
        ?bool   $archiver,
        ?string $nomVille,
        ?int    $codePostal,
        ?string $pays,
        ?string $adresse,
        ?string $emailPerso,
        ?string $numEtudiant,
        ?string $codeSexe,
        ?int    $idgroupe,
        ?int    $annee,
        ?string $dateNaissance,
        ?string $loginLDAP,
        string  $prenom,
    )
    {
        parent::__construct($idUtilisateur, $email, $nom, $numTelephone, $bio, []);
        $this->idUtilisateur = $idUtilisateur;
        $this->prenom = $prenom;
        $this->loginLDAP = $loginLDAP;
        $this->numEtudiant = $numEtudiant;
        $this->adresse = $adresse;
        $this->dateNaissance = $dateNaissance;
        $this->emailPerso = $emailPerso;
        $this->codeSexe = $codeSexe;
        $this->idgroupe = $idgroupe;
        $this->nomVille = $nomVille;
        $this->codePostal = $codePostal;
        $this->pays = $pays;
        $this->archiver = $archiver;
        $this->annee = $annee;
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
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
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
        return $this->numEtudiant;
    }

    public function setNumEtudiant(?string $numEtudiant): void
    {
        $this->numEtudiant = $numEtudiant;
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
        return $this->emailPerso;
    }

    public function setEmailPerso(?string $emailPerso): void
    {
        $this->emailPerso = $emailPerso;
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
        return $this->nomVille;
    }

    public function setNomVille(?string $nomVille): void
    {
        $this->nomVille = $nomVille;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): void
    {
        $this->codePostal = $codePostal;
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
        return $this->$nomColonne;
    }


}