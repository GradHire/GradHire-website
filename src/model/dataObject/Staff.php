<?php

namespace app\src\model\dataObject;

use app\src\model\dataObject\Utilisateur;

class Staff extends Utilisateur
{

    private int $idUtilisateur;
    private string $prenomUtilisateurLDAP;
    private string $loginLDAP;
    private string $role;
    private ?string $mailuni;

    public function __construct($idUtilisateur, $bio, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur, $prenomUtilisateurLDAP, $loginLDAP, $role, $mailuni)
    {
        parent::__construct($idUtilisateur, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur, $bio);
        $this->idUtilisateur = $idUtilisateur;
        $this->prenomUtilisateurLDAP = $prenomUtilisateurLDAP;
        $this->loginLDAP = $loginLDAP;
        $this->role = $role;
        $this->mailUni = $mailuni;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
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
        return $this->mailUni;
    }

    public function setMailuni(?string $mailuni): void
    {
        $this->mailUni = $mailuni;
    }

}