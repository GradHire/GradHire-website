<?php

namespace app\src\model\dataObject;

class Staff extends Utilisateur
{
    private int $idutilisateur;
    private ?string $role;
    private string $loginldap;
    private string $prenom;
    private ?int $archiver;
    private ?int $idtuteurentreprise;

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

    public function getIdtuteurentreprise(): ?int
    {
        return $this->idtuteurentreprise;
    }

    public function setIdtuteurentreprise(?int $idtuteurentreprise): void
    {
        $this->idtuteurentreprise = $idtuteurentreprise;
    }


    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?int $idutilisateur): void
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
        return $this->loginldap;
    }

    public function setLoginLdap(string $loginLdap): void
    {
        $this->loginldap = $loginLdap;
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
