<?php

namespace app\src\model\dataObject;
use app\src\model\dataObject\AbstractDataObject;

class Soutenance extends AbstractDataObject{

    private int $id_soutenance;
    private int $num_convention;
    private int $id_tuteur_prof;
    private int $id_tuteur_enterprise;
    private int $id_professeur;
    private \DateTime $debut_soutenance;
    private \DateTime $fin_soutenance;

    /**
     * @throws \Exception
     */
    public function __construct(array $attributes){

        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                if ($key == "debut_soutenance" || $key == "fin_soutenance") {
                    $this->$key = new \DateTime($value);
                } else {
                    $this->$key = $value;
                }
            }
    }

    public function getIdSoutenance(): int
    {
        return $this->id_soutenance;
    }

    public function setIdSoutenance(int $id_soutenance): void
    {
        $this->id_soutenance = $id_soutenance;
    }

    public function getNumConvention(): int
    {
        return $this->num_convention;
    }

    public function setNumConvention(int $num_convention): void
    {
        $this->num_convention = $num_convention;
    }

    public function getIdTuteurProf(): int
    {
        return $this->id_tuteur_prof;
    }

    public function setIdTuteurProf(int $id_tuteur_prof): void
    {
        $this->id_tuteur_prof = $id_tuteur_prof;
    }

    public function getIdTuteurEnterprise(): int
    {
        return $this->id_tuteur_enterprise;
    }

    public function setIdTuteurEnterprise(int $id_tuteur_enterprise): void
    {
        $this->id_tuteur_enterprise = $id_tuteur_enterprise;
    }

    public function getIdProfesseur(): int
    {
        return $this->id_professeur;
    }

    public function setIdProfesseur(int $id_professeur): void
    {
        $this->id_professeur = $id_professeur;
    }

    public function getDebutSoutenance(): \DateTime
    {
        return $this->debut_soutenance;
    }

    public function setDebutSoutenance(\DateTime $debut_soutenance): void
    {
        $this->debut_soutenance = $debut_soutenance;
    }

    public function getFinSoutenance(): \DateTime
    {
        return $this->fin_soutenance;
    }

    public function setFinSoutenance(\DateTime $fin_soutenance): void
    {
        $this->fin_soutenance = $fin_soutenance;
    }


    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}