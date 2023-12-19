<?php

namespace app\src\model\dataObject;

use DateTime;
use Exception;

class Soutenance extends AbstractDataObject
{

    private int $idsoutenance;
    private int $numconvention;
    private int $idtuteurprof;
    private int $idtuteurentreprise;
    private ?int $idprof;
    private ?int $idetudiant;
    private DateTime $debut_soutenance;
    private DateTime $fin_soutenance;

    /**
     * @throws Exception
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                if ($key == "debut_soutenance" || $key == "fin_soutenance") {
                    $this->$key = new DateTime($value);
                } else {
                    $this->$key = $value;
                }
            }
    }

    public function getIdtuteurentreprise(): int
    {
        return $this->idtuteurentreprise;
    }

    public function setIdtuteurentreprise(int $idtuteurentreprise): void
    {
        $this->idtuteurentreprise = $idtuteurentreprise;
    }

    public function getIdprof(): ?int
    {
        return $this->idprof;
    }

    public function setIdprof(?int $idprof): void
    {
        $this->idprof = $idprof;
    }

    public function getIdetudiant(): ?int
    {
        return $this->idetudiant;
    }

    public function setIdetudiant(?int $idetudiant): void
    {
        $this->idetudiant = $idetudiant;
    }

    public function getIdSoutenance(): int
    {
        return $this->idsoutenance;
    }

    public function setIdSoutenance(int $id_soutenance): void
    {
        $this->idsoutenance = $id_soutenance;
    }

    public function getNumConvention(): int
    {
        return $this->numconvention;
    }

    public function setNumConvention(int $num_convention): void
    {
        $this->numconvention = $num_convention;
    }

    public function getIdTuteurProf(): int
    {
        return $this->idtuteurprof;
    }

    public function setIdTuteurProf(int $id_tuteur_prof): void
    {
        $this->idtuteurprof = $id_tuteur_prof;
    }

    public function getIdTuteurEnterprise(): int
    {
        return $this->idtuteurentreprise;
    }

    public function setIdTuteurEnterprise(int $id_tuteur_enterprise): void
    {
        $this->idtuteurentreprise = $id_tuteur_enterprise;
    }

    public function getIdProfesseur(): ?int
    {
        return $this->idprof;
    }

    public function setIdProfesseur(int $id_professeur): void
    {
        $this->idprof = $id_professeur;
    }

    public function getDebutSoutenance(): DateTime
    {
        return $this->debut_soutenance;
    }

    public function setDebutSoutenance(DateTime $debut_soutenance): void
    {
        $this->debut_soutenance = $debut_soutenance;
    }

    public function getFinSoutenance(): DateTime
    {
        return $this->fin_soutenance;
    }

    public function setFinSoutenance(DateTime $fin_soutenance): void
    {
        $this->fin_soutenance = $fin_soutenance;
    }


    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}