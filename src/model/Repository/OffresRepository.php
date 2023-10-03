<?php

namespace app\src\model\Repository;
use app\src\model\DataObject\Offres;

class OffresRepository extends AbstractRepositoryObject
{

  private string $nomTable = "Offre";

  protected function getNomColonnes(): array
  {
    return [
      "duree",
      "thematique",
      "sujet",
      "nbJourTravailHebdo",
      "nbHeureTravailHebdo",
      "Gratification",
      "uniteGratification",
      "avantageNature",
      "dateDebut",
      "dateFin",
      "Statut",
      "anneeVisee"
    ];
  }

  protected function getNomTable(): string
  {
    return $this->nomTable;
  }

  protected function construireDepuisTableau(array $dataObjectFormatTableau): Offres
  {
    return new Offres(
      $dataObjectFormatTableau['idOffre'],
      $dataObjectFormatTableau['duree'],
      $dataObjectFormatTableau['thematique'],
      $dataObjectFormatTableau['sujet'],
      $dataObjectFormatTableau['nbJourTravailHebdo'],
      $dataObjectFormatTableau['nbHeureTravailHebdo'],
      $dataObjectFormatTableau['Gratification'],
      $dataObjectFormatTableau['uniteGratification'],
      $dataObjectFormatTableau['avantageNature'],
      $dataObjectFormatTableau['dateDebut'],
      $dataObjectFormatTableau['dateFin'],
      $dataObjectFormatTableau['Statut'],
      $dataObjectFormatTableau['anneeVisee'],
      $dataObjectFormatTableau['idAnnee'],
      $dataObjectFormatTableau['idUtilisateur']
    );
  }
}
