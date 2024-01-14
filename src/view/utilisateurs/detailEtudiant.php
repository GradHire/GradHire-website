<?php
/** @var $utilisateur Etudiant */

use app\src\model\dataObject\Etudiant;
use app\src\view\components\ui\Detail;

Detail::render([$utilisateur]);
Detail::addDetail("Prenom", $utilisateur->getPrenom());
Detail::addDetail("Nom", $utilisateur->getNom());
Detail::addDetail("Login LDAP", $utilisateur->getLoginLDAP());
Detail::addDetail("Email", $utilisateur->getEmail());
Detail::addDetail("Numéro de téléphone", $utilisateur->getNumtelephone());
Detail::addDetail("Numéro Etudiant", $utilisateur->getNumEtudiant());
Detail::addDetail("Adresse", $utilisateur->getAdresse());
Detail::addDetail("Email Perso", $utilisateur->getEmailPerso());
Detail::addDetail("Sexe", $utilisateur->getCodeSexe());
Detail::addDetail("Groupe", $utilisateur->getIdgroupe());
Detail::addDetail("Nom Ville", $utilisateur->getNomVille());
Detail::addDetail("Code Postal", $utilisateur->getCodePostal());
Detail::addDetail("Pays", $utilisateur->getPays());
Detail::addDetail("Biographie", $utilisateur->getBio());
Detail::addDetailBool("Archiver", $utilisateur->getArchiver());
Detail::addDetail("Date de Naissance", $utilisateur->getDateNaissance());
Detail::addDetail("Année", $utilisateur->getAnnee());
Detail::end();

?>


