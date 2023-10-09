<?php

namespace app\src\model\repository;

use app\src\model\dataObject\Entreprise;

class EntrepriseRepository extends AbstractRepository
{
    private string $nomTable = "Entreprise";

    protected function getNomTable(): string
    {
        return $this->nomTable;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Entreprise
    {
        return new Entreprise(
            $dataObjectFormatTableau['idutilisateur'],
            $dataObjectFormatTableau['statutjuridique'],
            $dataObjectFormatTableau['typestructure'],
            $dataObjectFormatTableau['effectif'],
            $dataObjectFormatTableau['codenaf'],
            $dataObjectFormatTableau['fax'],
            $dataObjectFormatTableau['siteweb'],
            $dataObjectFormatTableau['siret'],
            $dataObjectFormatTableau['validee']
        );
    }

    protected function getNomColonnes(): array
    {
        return [
            "idutilisateur",
            "statutjuridique",
            "typestructure",
            "effectif",
            "codenaf",
            "fax",
            "siteweb",
            "siret",
            "validee"
        ];
    }
}