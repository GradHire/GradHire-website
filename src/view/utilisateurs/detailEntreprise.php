<?php
/** @var $utilisateur Entreprise */

use app\src\model\dataObject\Entreprise;
use app\src\model\View;
use app\src\view\components\ui\Detail;

$this->title = 'Entreprise';
View::setCurrentSection('Utilisateurs');

Detail::render([$utilisateur]);
Detail::addDetail("Effectif", $utilisateur->getEffectif());
Detail::addDetail("Code NAF", $utilisateur->getCodenaf());
Detail::addDetail("Fax", $utilisateur->getFax());
Detail::addDetailLink("Site web", $utilisateur->getSiteweb());
Detail::addDetail("SIRET", $utilisateur->getSiret());
Detail::addDetail("Statut juridique", $utilisateur->getStatutjuridique());
Detail::end();

?>
