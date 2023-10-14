<?php
namespace app\src\model\dataObject;
use app\src\model\dataObject\Utilisateur;
class Etudiant extends Utilisateur {

    private int $idUtilisateur;
    private ?string $mailperso;
    private ?string $codesexeetudiant;
    private ?string $numEtudiant;
    private ?string $datenaissance;
    private ?string $idgroupe;
    private int $annee;
    private string $prenomutilisateurldap;
    private string $loginLDAP;

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function __construct(
        int $idUtilisateur,
        string $bio,
        string $emailUtilisateur,
        string $nomutilisateur,
        ?string $numTelUtilisateur,
        ?string $mailperso,
        ?string $codesexeetudiant,
        ?string $numEtudiant,
        ?string $datenaissance,
        ?string $idgroupe,
        int $annee,
        string $prenomutilisateurldap,
        string $loginLDAP
    )
    {
        parent::__construct($idUtilisateur, $emailUtilisateur,$nomutilisateur, $numTelUtilisateur,$bio);
        $this->idUtilisateur = $idUtilisateur;
        $this->mailperso = $mailperso;
        $this->codesexeetudiant = $codesexeetudiant;
        $this->numEtudiant = $numEtudiant;
        $this->datenaissance = $datenaissance;
        $this->idgroupe = $idgroupe;
        $this->annee = $annee;
        $this->prenomutilisateurldap = $prenomutilisateurldap;
        $this->loginLDAP = $loginLDAP;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }


    public function getMailperso(): ?string
    {
        return $this->mailperso;
    }

    public function setMailperso(?string $mailperso): void
    {
        $this->mailperso = $mailperso;
    }

    public function getCodesexeetudiant(): ?string
    {
        return $this->codesexeetudiant;
    }

    public function setCodesexeetudiant(?string $codesexeetudiant): void
    {
        $this->codesexeetudiant = $codesexeetudiant;
    }

    public function getNumEtudiant(): ?int
    {
        return $this->numEtudiant;
    }

    public function setNumEtudiant(?int $numEtudiant): void
    {
        $this->numEtudiant = $numEtudiant;
    }

    public function getDatenaissance(): ?string
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?string $datenaissance): void
    {
        $this->datenaissance = $datenaissance;
    }

    public function getIdgroupe(): ?int
    {
        return $this->idgroupe;
    }

    public function setIdgroupe(?int $idgroupe): void
    {
        $this->idgroupe = $idgroupe;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }


    public function getPrenomutilisateurldap(): string
    {
        return $this->prenomutilisateurldap;
    }

    public function setPrenomutilisateurldap(string $prenomutilisateurldap): void
    {
        $this->prenomutilisateurldap = $prenomutilisateurldap;
    }

    public function getLoginLDAP(): string
    {
        return $this->loginLDAP;
    }

    public function setLoginLDAP(string $loginLDAP): void
    {
        $this->loginLDAP = $loginLDAP;
    }




}