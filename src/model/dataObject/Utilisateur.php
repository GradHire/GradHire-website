<?php

namespace app\src\model\dataObject;

class Utilisateur extends AbstractDataObject
{
    private ?int $idutilisateur;
    private ?string $numtelephone;
    private string $nom;
    private string $email;
    private ?string $bio;


    public function __construct(array $attributes)
    {
        $validAttributes = array_intersect_key(
            $attributes,
            array_flip(
                array_filter(
                    array_keys(get_class_vars(self::class)),
                    function ($key) {
                        return !is_null($key);
                    }
                )
            )
        );

        foreach ($validAttributes as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getIdutilisateur(): ?int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getNumtelephone(): ?string
    {
        return $this->numtelephone;
    }

    public function setNumtelephone(?string $numtelephone): void
    {
        $this->numtelephone = $numtelephone;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    public function getRole(): ?string
    {
        return "utilisateur";
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

}
