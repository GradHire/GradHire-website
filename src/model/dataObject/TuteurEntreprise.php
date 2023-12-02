<?php

namespace app\src\model\dataObject;

class TuteurEntreprise extends Utilisateur
{
    private int $idutilisateur;
    private ?string $prenom;
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

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): void
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

    public function getIdentreprise(): int
    {
        return $this->identreprise;
    }

    public function setIdentreprise(int $identreprise): void
    {
        $this->identreprise = $identreprise;
    }


    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

}