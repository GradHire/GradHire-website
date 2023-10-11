<?php
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Utilisateur;
class Etudiant extends Utilisateur {

    private int $idUtilisateur;
    private string $mailperso;
    private ?string $codesexeetudiant;
    private ?string $datenaissanceetudiant;
    private ?int $idgroupe;
    private int $annee;
    private string $nom;

    protected function getValueColonne(string $nomColonne): string
    {
        return $this->$nomColonne;
    }

    public function __construc(
        $idUtilisateur,
        $mailperso,
        $codesexeetudiant,
        $datenaissanceetudiant,
        $idgroupe,
        $annee,
        $nom
    ){
        $this->idUtilisateur = $idUtilisateur;
        $this->mailperso = $mailperso;
        $this->codesexeetudiant = $codesexeetudiant;
        $this->datenaissanceetudiant = $datenaissanceetudiant;
        $this->idgroupe = $idgroupe;
        $this->annee = $annee;
        $this->nom = $nom;
    }

    public function getEtudiantbyId(int $id): Etudiant
    {
        $sql = "SELECT * FROM etudiant WHERE idutilisateur = :id";
        $stmt = $this->getBdd()->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $etudiant = new Etudiant(
            $result["idutilisateur"],
            $result["mailperso"],
            $result["codesexeetudiant"],
            $result["datenaissanceetudiant"],
            $result["idgroupe"],
            $result["annee"],
            $result["nom"]
        );
        return $etudiant;
    }
}