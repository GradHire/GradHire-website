# GradHire - Le projet de BUT Informatique

Ce document offre un aperçu de la plateforme GradHire, développée par les étudiants de BUT Informatique à l'IUT Montpellier-Sète pendant leur troisième semestre. GradHire agit comme un pont entre la formation académique et les exigences du marché de l'emploi.

## Équipe

- Marius BROUTY
- Clément GARRO
- Giovanni GOZZO
- Daniil HIRCHYTS

Année Académique : 2023-2024

---

## Introduction

GradHire est une plateforme web conçue pour faciliter l'entrée des étudiants dans la vie professionnelle en offrant un espace centralisé pour les offres de stages et de programmes en alternance des entreprises.

## Description du Projet

GradHire est une plateforme innovante conçue pour faciliter les connexions entre les étudiants et le monde professionnel. Elle offre un ensemble complet de fonctionnalités pour améliorer l'expérience des utilisateurs, qu'ils soient des entreprises ou des étudiants.

---

### Fonctionnalités pour les Entreprises

- **Inscription :** Permet aux entreprises de s'enregistrer en fournissant des informations essentielles.
- **Publication des Offres :** Facilite la création et la mise en ligne d'offres de stages ou d'alternances.
- **Gestion des Offres :** Inclut l'importation des offres via CSV, la création, la validation, la modification et la suppression des offres.
- **Communication Directe :** Système permettant de contacter facilement les candidats et d'envoyer des newsletters.

### Fonctionnalités pour les Étudiants

- **Consultation des Offres :** Possibilité pour les étudiants de rechercher, de filtrer et de consulter les offres disponibles.
- **Candidature aux Offres :** Permet aux étudiants de postuler aux offres directement sur la plateforme.
- **Gestion des Candidatures :** Suivre l'état des candidatures, accepter des offres et gérer le processus de l'entrevue à la soutenance.
- **Calendrier :** Un calendrier pour planifier les soutenances et les visites.

### Fonctionnalités Académiques

- **Gestion des Soutenances :** Outils pour planifier et consulter les soutenances et visites.
- **Système des Avis et Comptes Rendus :** Publication et gestion des avis relatifs à des soutenances ou à l'expérience utilisateur.
- **Gestion des Entreprises :** Permet aux enseignants de consulter et de gérer les comptes des entreprises.
- **Gestion des Offres :** Permet aux enseignants de consulter et de gérer les offres.
- **Gestion des Candidatures :** Permet aux enseignants de consulter et de gérer les candidatures.
- **Gestion des Comptes :** Permet aux enseignants de consulter et de gérer les comptes des utilisateurs.

### Sécurité et Personnalisation

- **Sécurité :** Protection contre les injections SQL et XSS, et cryptage des mots de passe pour assurer la confidentialité et l'intégrité des données.
- **Personnalisation :** Possibilité pour les utilisateurs de personnaliser leur expérience sur la plateforme, notamment en ajustant leur barre latérale.

### Outils d'Assistance et Ressources Supplémentaires

- **Chatbot :** Intégration d'un chatbot pour l'assistance aux utilisateurs et la réponse aux questions fréquentes.
- **Simulation de Pstage :** Un simulateur pour épauler les étudiants dans la préparation de leurs stages.
- **Statistiques :** Un tableau de bord pour visualiser les statistiques de la plateforme.
- **Ressources :** Un espace pour les ressources supplémentaires telles que les liens vers les sites des entreprises.

### Analyse et Conception

- **Diagrammes UML :** Utilisation de diagrammes Use Case et de séquences pour une meilleure compréhension des interactions utilisateur.
- **Modélisation de la Base de Données :** Un modèle Entité-Relation détaillé pour un système robuste et structuré.

### Déploiement et Maintenance

- **Solutions de Conteneurisation :** Utilisation de Docker pour un déploiement simplifié et fiable de la plateforme.
- **Maintenance Facilitée :** Structure du projet basée sur le modèle MVC pour une évolutivité et une maintenance efficace.
- **Documentation :** Documentation complète pour faciliter la maintenance et l'extension du projet.

GradHire représente une solution clé pour les organisations cherchant à recruter des talents et pour les étudiants désireux de se lancer dans des carrières enrichissantes en adéquation avec leur formation académique.

## Analyse et Conception

Utilisation de différents diagrammes UML pour affiner notre base de code et maximiser l'adhésion aux principes SOLID.

## Système d'Authentification

Processus de connexion détaillé pour différents profils d'utilisateur tels que les comptes professionnels, LDAP et la supervision des étudiants.

## Gestion de Compte

Outils pour la maintenance complète du compte, y compris les changements de mot de passe et l'accès au tableau de bord pour des statistiques et gestion.

## Gestion des Offres

Fonctionnalités pour l'importation d'offres via CSV, la gestion des publications et l'envoi de newsletters aux abonnés.

## Sécurité et Personnalisation

Des fonctionnalités de sécurité de pointe telles que le stockage de mot de passe crypté et la validation des cookies, couplées à des expériences utilisateur personnalisables.

## Diagrammes

Des diagrammes complets, y compris des modèles Entité-Relation, des diagrammes de Cas d'Utilisation, et des Diagrammes de Classe sont fournis pour illustrer la conception du système.

_Pour un ensemble complet de diagrammes, accédez à notre [Diagrammes dans le rapport](https://drive.google.com/file/d/1cxeMwlNlAfpbL-QIwYSCCJOAMf-A2NXo/view)._

---

## Démarrage Rapide

- **Configuration avec Docker :** Instructions pour configurer le projet en utilisant des conteneurs Docker.
- **Configuration Locale :**
> (1) Clonez le dépôt sur votre machine locale.
> 
>(2) Installez les dépendances avec `npm install`.
> 
>(3) Démarrez le watcher de TailwindCSS avec `npm run watch`.
> 
>(4) Démarrez le serveur avec `npm run dev`.
> 
>(5) Accédez à `localhost:8080` pour voir le site.

_Pour des instructions de configuration complètes et des tutoriels, visitez [Utilisation de notre site](https://drive.google.com/file/d/1cxeMwlNlAfpbL-QIwYSCCJOAMf-A2NXo/view)._

---

## Licence

La permission est par la présente accordée, gratuitement, à toute personne obtenant une copie
de ce logiciel et des fichiers de documentation associés (le "Logiciel"), pour traiter
le Logiciel sans restriction, y compris sans limitation les droits
d'utilisation, de copie, de modification, de fusion, de publication, de distribution, de sous-licence, et/ou de vente
des copies du Logiciel, et de permettre aux personnes à qui le Logiciel est
fourni de le faire, sous réserve des conditions suivantes:

La mention de copyright ci-dessus et cet avis de permission doivent être inclus dans tous
les copies ou portions substantielles du Logiciel.

LE LOGICIEL EST FOURNI "TEL QUEL", SANS GARANTIE D'AUCUNE SORTE, EXPRESSE OU
IMPLICITE, Y COMPRIS MAIS NON LIMITÉ AUX GARANTIES DE QUALITÉ MARCHANDE,
D'ADÉQUATION À UN USAGE PARTICULIER ET D'ABSENCE DE CONTREFAÇON. EN AUCUN CAS, LES
AUTEURS OU TITULAIRES DU DROIT D'AUTEUR SERONT RESPONSABLES DE TOUTE RÉCLAMATION, DOMMAGES OU AUTRE
RESPONSABILITÉ, QUE CE SOIT DANS UNE ACTION DE CONTRAT, DE TORT OU AUTRE, DÉCOULANT DE,
HORS DE OU EN RELATION AVEC LE LOGICIEL OU L'UTILISATION OU D'AUTRES TRANSACTIONS DANS LE
LOGICIEL.
---

Pour une compréhension plus détaillée de GradHire et ses aspects techniques, veuillez vous référer au rapport complet.

_Des tutoriels et ressources supplémentaires peuvent être trouvés [ici](https://drive.google.com/file/d/1cxeMwlNlAfpbL-QIwYSCCJOAMf-A2NXo/view)._

