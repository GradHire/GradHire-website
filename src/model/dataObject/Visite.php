<?php

namespace app\src\model\dataObject;

class Visite extends AbstractDataObject
{
    private int $id_etudiant;
    private int $id_tuteur_univ;
    private int $id_tuteur_pro;
    private \DateTime $debut_visite;
    private \DateTime $fin_visite;

    /**
     * @throws \Exception
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value)
            if (property_exists($this, $key)) {
                if ($key == "debut_visite" || $key == "fin_visite") {
                    $this->$key = new \DateTime($value);
                } else {
                    $this->$key = $value;
                }
            }
    }

    public function getIdEtudiant(): int
    {
        return $this->id_etudiant;
    }

    public function setIdEtudiant(int $id_etudiant): void
    {
        $this->id_etudiant = $id_etudiant;
    }

    public function getIdTuteurUniv(): int
    {
        return $this->id_tuteur_univ;
    }

    public function setIdTuteurUniv(int $id_tuteur_univ): void
    {
        $this->id_tuteur_univ = $id_tuteur_univ;
    }

    public function getIdTuteurPro(): int
    {
        return $this->id_tuteur_pro;
    }

    public function setIdTuteurPro(int $id_tuteur_pro): void
    {
        $this->id_tuteur_pro = $id_tuteur_pro;
    }

    public function getDebutVisite(): \DateTime
    {
        return $this->debut_visite;
    }

    public function setDebutVisite(\DateTime $debut_visite): void
    {
        $this->debut_visite = $debut_visite;
    }

    public function getFinVisite(): \DateTime
    {
        return $this->fin_visite;
    }

    public function setFinVisite(\DateTime $fin_visite): void
    {
        $this->fin_visite = $fin_visite;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}