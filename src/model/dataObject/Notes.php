<?php

namespace app\src\model\dataObject;

class Notes extends AbstractDataObject
{
    private int $idnote;
    private ?string $etudiant;
    private ?string $presenttuteur;
    private ?string $renduretard;
    private ?int $noterapport;
    private ?string $commentairerapport;
    private ?int $noteoral;
    private ?string $commentaireoral;
    private ?int $noterelation;
    private ?string $langage;
    private ?string $nouveau;
    private ?string $difficulte;
    private ?int $notedemarche;
    private ?int $noteresultat;
    private ?string $commentaireresultat;
    private ?string $recherche;
    private ?string $recontact;
    private ?int $idsoutenance;

    /**
     * @throws \Exception
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
    }

    public function getIdnote(): int
    {
        return $this->idnote;
    }

    public function setIdnote(int $idnote): void
    {
        $this->idnote = $idnote;
    }

    public function getEtudiant(): int
    {
        return $this->etudiant;
    }

    public function setEtudiant(int $etudiant): void
    {
        $this->etudiant = $etudiant;
    }

    public function getPresenttuteur(): int
    {
        return $this->presenttuteur;
    }

    public function setPresenttuteur(int $presenttuteur): void
    {
        $this->presenttuteur = $presenttuteur;
    }

    public function getRenduretard(): int
    {
        return $this->renduretard;
    }

    public function setRenduretard(int $renduretard): void
    {
        $this->renduretard = $renduretard;
    }

    public function getNoterapport(): int
    {
        return $this->noterapport;
    }

    public function setNoterapport(int $noterapport): void
    {
        $this->noterapport = $noterapport;
    }

    public function getCommentairerapport(): string
    {
        return $this->commentairerapport;
    }

    public function setCommentairerapport(string $commentairerapport): void
    {
        $this->commentairerapport = $commentairerapport;
    }

    public function getNoteoral(): int
    {
        return $this->noteoral;
    }

    public function setNoteoral(int $noteoral): void
    {
        $this->noteoral = $noteoral;
    }

    public function getCommentaireoral(): string
    {
        return $this->commentaireoral;
    }

    public function setCommentaireoral(string $commentaireoral): void
    {
        $this->commentaireoral = $commentaireoral;
    }

    public function getNoterelation(): int
    {
        return $this->noterelation;
    }

    public function setNoterelation(int $noterelation): void
    {
        $this->noterelation = $noterelation;
    }

    public function getLangage(): string
    {
        return $this->langage;
    }

    public function setLangage(string $langage): void
    {
        $this->langage = $langage;
    }

    public function getNouveau(): string
    {
        return $this->nouveau;
    }

    public function setNouveau(string $nouveau): void
    {
        $this->nouveau = $nouveau;
    }

    public function getDifficulte(): string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): void
    {
        $this->difficulte = $difficulte;
    }

    public function getNotedemarche(): ?int
    {
        return $this->notedemarche;
    }

    public function setNotedemarche(int $notedemarche): void
    {
        $this->notedemarche = $notedemarche;
    }

    public function getNoteresultat(): int
    {
        return $this->noteresultat;
    }

    public function setNoteresultat(int $noteresultat): void
    {
        $this->noteresultat = $noteresultat;
    }

    public function getCommentaireresultat(): string
    {
        return $this->commentaireresultat;
    }

    public function setCommentaireresultat(string $commentaireresultat): void
    {
        $this->commentaireresultat = $commentaireresultat;
    }

    public function getRecherche(): string
    {
        return $this->recherche;
    }

    public function setRecherche(string $recherche): void
    {
        $this->recherche = $recherche;
    }

    public function getRecontact(): string
    {
        return $this->recontact;
    }

    public function setRecontact(string $recontact): void
    {
        $this->recontact = $recontact;
    }

    public function getIdsoutenance(): int
    {
        return $this->idsoutenance;
    }

    public function setIdsoutenance(int $idsoutenance): void
    {
        $this->idsoutenance = $idsoutenance;
    }

    public function noteFinal(): float
    {
        return ($this->noterapport * 20 + $this->noteoral * 10 + $this->noterelation * 10 + $this->notedemarche * 20 + $this->noteresultat * 20) / 80;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}
