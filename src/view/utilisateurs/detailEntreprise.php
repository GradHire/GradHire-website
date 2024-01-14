<?php
/** @var $utilisateur \app\src\model\dataObject\Entreprise */

use app\src\model\Application;
use app\src\model\repository\UtilisateurRepository;
use app\src\view\components\ui\Detail;

Detail::render([$utilisateur]);
Detail::addDetail("Effectif", $utilisateur->getEffectif());
Detail::addDetail("Code NAF", $utilisateur->getCodenaf());
Detail::addDetail("Fax", $utilisateur->getFax());
Detail::addDetailLink("Site web", $utilisateur->getSiteweb());
Detail::addDetail("SIRET", $utilisateur->getSiret());
Detail::addDetail("Statut juridique", $utilisateur->getStatutjuridique());
Detail::end();

?>
