<?php
/** @var $utilisateur \app\src\model\dataObject\Staff */

use app\src\model\View;
use app\src\view\components\ui\Detail;

$this->title = 'Utilisateur';
View::setCurrentSection('Utilisateurs');

Detail::render([$utilisateur]);
Detail::addDetail("Prenom", $utilisateur->getNom());
Detail::addDetail("Nom", $utilisateur->getPrenom());
Detail::addDetail("Rôle", $utilisateur->getRole());
Detail::addDetail("Login LDAP", $utilisateur->getLoginLDAP());
Detail::addDetail("Email", $utilisateur->getEmail());
Detail::addDetail("Numéro de téléphone", $utilisateur->getNumtelephone());
Detail::addDetail("Biographie", $utilisateur->getBio());
Detail::addDetailBool("Archiver", $utilisateur->getArchiver());
Detail::end();
?>


