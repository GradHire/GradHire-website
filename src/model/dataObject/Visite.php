<?php

namespace app\src\model\dataObject;

class Visite extends AbstractDataObject
{
    private int $num_convention;
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

    public function getNumConvention(): int
    {
        return $this->num_convention;
    }

    public function setNumConvention(int $num_convention): void
    {
        $this->num_convention = $num_convention;
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