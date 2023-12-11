<?php

namespace app\src\model\dataObject;

class Tuteur extends Utilisateur
{

    private int $idutilisateur;
    private int $archiver;
    private string $prenom;
    private ?string $fonction;
    private int $identreprise;

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

    public function getArchiver(): int
    {
        return $this->archiver;
    }

    public function setArchiver(int $archiver): void
    {
        $this->archiver = $archiver;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): void
    {
        $this->idutilisateur = $idUtilisateur;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): void
    {
        $this->fonction = $fonction;
    }

    public function getIdEntreprise(): int
    {
        return $this->identreprise;
    }

    public function setIdEntreprise(int $idEntreprise): void
    {
        $this->identreprise = $idEntreprise;
    }

    public function getRole(): ?string
    {
        return "tuteur";
    }
}
