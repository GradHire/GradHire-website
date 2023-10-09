<?php

namespace app\src\model\dataObject;

class Entreprise extends AbstractDataObject
{
    private int $idutilisateur;
    private ?string $statutjuridique;
    private ?string $typestructure;
    private ?string $effectif;
    private ?string $codenaf;
    private ?string $fax;
    private ?string $siteweb;
    private int $siret;
    private int $validee;

    /**
     * @param int $idutilisateur
     * @param string|null $statutjuridique
     * @param string|null $typestructure
     * @param string|null $effectif
     * @param string|null $codenaf
     * @param string|null $fax
     * @param string|null $siteweb
     * @param int $siret
     * @param int $validee
     */
    public function __construct(int $idutilisateur, ?string $statutjuridique, ?string $typestructure, ?string $effectif, ?string $codenaf, ?string $fax, ?string $siteweb, int $siret, int $validee)
    {
        $this->idutilisateur = $idutilisateur;
        $this->statutjuridique = $statutjuridique;
        $this->typestructure = $typestructure;
        $this->effectif = $effectif;
        $this->codenaf = $codenaf;
        $this->fax = $fax;
        $this->siteweb = $siteweb;
        $this->siret = $siret;
        $this->validee = $validee;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

}