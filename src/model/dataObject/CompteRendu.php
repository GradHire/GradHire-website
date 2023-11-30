<?php

namespace app\src\model\dataObject;

class CompteRendu
{
    private int $numconvention;
    private int $idtuteurprof;
    private int $idetudiant;
    private string $commentaireprof;
    private int $idtuteurentreprise;
    private string $commentaireentreprise;

    public function __construct(array $dataObjectFormatTableau)
    {
        foreach ($dataObjectFormatTableau as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getNumconvention(): int
    {
        return $this->numconvention;
    }

    public function setNumconvention(int $numconvention): void
    {
        $this->numconvention = $numconvention;
    }

    public function getIdtuteurprof(): int
    {
        return $this->idtuteurprof;
    }

    public function setIdtuteurprof(int $idtuteurprof): void
    {
        $this->idtuteurprof = $idtuteurprof;
    }

    public function getIdetudiant(): int
    {
        return $this->idetudiant;
    }

    public function setIdetudiant(int $idetudiant): void
    {
        $this->idetudiant = $idetudiant;
    }

    public function getCommentaireprof(): string
    {
        return $this->commentaireprof;
    }

    public function setCommentaireprof(string $commentaireprof): void
    {
        $this->commentaireprof = $commentaireprof;
    }

    public function getIdtuteurentreprise(): int
    {
        return $this->idtuteurentreprise;
    }

    public function setIdtuteurentreprise(int $idtuteurentreprise): void
    {
        $this->idtuteurentreprise = $idtuteurentreprise;
    }

    public function getCommentaireentreprise(): string
    {
        return $this->commentaireentreprise;
    }

    public function setCommentaireentreprise(string $commentaireentreprise): void
    {
        $this->commentaireentreprise = $commentaireentreprise;
    }


}