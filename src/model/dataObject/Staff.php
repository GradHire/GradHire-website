<?php
namespace app\src\model\dataObject;
use app\src\model\dataObject\Utilisateur;

class Staff extends Utilisateur {
    private int $idUtilisateur;
    private ?string $bio;
    private string $emailUtilisateur;
    private string $nomUtilisateur;
    private ?string $numTelUtilisateur;
    private string $prenomUtilisateurLDAP;
    private string $loginLDAP;
    private string $role;
    private ?string $mailuni;

    public function __construct($idUtilisateur, $bio, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur, $prenomUtilisateurLDAP, $loginLDAP, $role, $mailuni)
    {
        parent::__construct($idUtilisateur, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur);
        $this->idUtilisateur = $idUtilisateur;
        $this->bio = $bio;
        $this->emailUtilisateur = $emailUtilisateur;
        $this->nomUtilisateur = $nomUtilisateur;
        $this->numTelUtilisateur = $numTelUtilisateur;
        $this->prenomUtilisateurLDAP = $prenomUtilisateurLDAP;
        $this->loginLDAP = $loginLDAP;
        $this->role = $role;
        $this->mailuni = $mailuni;
        echo '<br> Staff construct <br>';
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    public function getEmailUtilisateur(): string
    {
        return $this->emailUtilisateur;
    }

    public function setEmailUtilisateur(string $emailUtilisateur): void
    {
        $this->emailUtilisateur = $emailUtilisateur;
    }

    public function getNomUtilisateur(): string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): void
    {
        $this->nomUtilisateur = $nomUtilisateur;
    }

    public function getNumTelUtilisateur(): ?string
    {
        return $this->numTelUtilisateur;
    }

    public function setNumTelUtilisateur(?string $numTelUtilisateur): void
    {
        $this->numTelUtilisateur = $numTelUtilisateur;
    }

    public function getPrenomUtilisateurLDAP(): string
    {
        return $this->prenomUtilisateurLDAP;
    }

    public function setPrenomUtilisateurLDAP(string $prenomUtilisateurLDAP): void
    {
        $this->prenomUtilisateurLDAP = $prenomUtilisateurLDAP;
    }

    public function getLoginLDAP(): string
    {
        return $this->loginLDAP;
    }

    public function setLoginLDAP(string $loginLDAP): void
    {
        $this->loginLDAP = $loginLDAP;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getMailuni(): ?string
    {
        return $this->mailuni;
    }

    public function setMailuni(?string $mailuni): void
    {
        $this->mailuni = $mailuni;
    }

}