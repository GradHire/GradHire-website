<?php
use app\src\model\repository\AbstractRepository;
use app\src\model\repository\UtilisateurRepository;
class EtudiantRepository extends UtilisateurRepository{

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Etudiant
    {
        // TODO: Implement construireDepuisTableau() method.
    }

    protected function getNomColonnes(): array
    {
        return [
            "idUtilisateur",
            "mailperso",
            "codesexeetudiant",
            "datenaissanceetudiant",
            "idgroupe",
            "annee",
            "nom"
        ];
    }

    protected function getNomTable(): string
    {
        return "Etudiant";
    }

    protected function getValueColonne(string $nomColonne): array
    {
        return [
            "idUtilisateur",
            "mailperso",
            "codesexeetudiant",
            "datenaissanceetudiant",
            "idgroupe",
            "annee",
            "nom"
        ];
    }
}