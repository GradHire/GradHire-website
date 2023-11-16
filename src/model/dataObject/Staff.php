<?php

namespace app\src\model\dataObject;

class Staff extends Utilisateur
{
    private int $idutilisateur;
    private ?string $role;
    private string $loginLdap;
    private string $prenom;
    private ?int $archiver;

    public function __construct($idutilisateur, $email, $nom, $numtelephone, $bio, $archiver, $loginLdap, $prenom, $role)
    {
        parent::__construct($idutilisateur, $email, $nom, $numtelephone, $bio);
        $this->idutilisateur = $idutilisateur;
        $this->role = $role;
        $this->loginLdap = $loginLdap;
        $this->prenom = $prenom;
        $this->archiver = $archiver;
    }


    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    public function getLoginLdap(): string
    {
        return $this->loginLdap;
    }

    public function setLoginLdap(string $loginLdap): void
    {
        $this->loginLdap = $loginLdap;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getArchiver(): ?int
    {
        return $this->archiver;
    }

    public function setArchiver(?int $archiver): void
    {
        $this->archiver = $archiver;
    }


}