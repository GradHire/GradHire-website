<?php
/** @var $utilisateur Tuteur */

use app\src\model\dataObject\Tuteur;
use app\src\model\View;
use app\src\view\components\ui\Detail;

$this->title = 'Tuteur';
View::setCurrentSection('Utilisateurs');

Detail::render([$utilisateur]);
Detail::addDetail("Prenom", $utilisateur->getPrenom());
Detail::addDetail("Nom", $utilisateur->getNom());
Detail::addDetail("Email", $utilisateur->getEmail());
Detail::addDetail("Numéro de téléphone", $utilisateur->getNumtelephone());
Detail::addDetail("Biographie", $utilisateur->getBio());
Detail::addDetail("Fonction Tuteur", $utilisateur->getFonction());
Detail::addDetail("ID Entreprise", $utilisateur->getIdentreprise());
Detail::end();

?>


