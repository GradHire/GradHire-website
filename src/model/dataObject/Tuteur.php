<?php
namespace app\src\model\dataObject;
use app\src\model\dataObject\Utilisateur;
class Tuteur extends Utilisateur {

    private int $idUtilisateur;
    private ?string $hash;
    private string $prenomtuteurp;
    private ?string $fonctiontuteurp;
    private int $identreprise;

    public function __construct(int $idUtilisateur,?string $bio,string $emailUtilisateur,string $nomUtilisateur, ?string $numTelUtilisateur,?string $hash,string $prenomtuteurp, ?string $fonctiontuteurp, int $identreprise)
    {
        parent::__construct($idUtilisateur, $emailUtilisateur, $nomUtilisateur, $numTelUtilisateur, $bio);
        $this->idUtilisateur = $idUtilisateur;
        $this->hash = $hash;
        $this->prenomtuteurp = $prenomtuteurp;
        $this->fonctiontuteurp = $fonctiontuteurp;
        $this->identreprise = $identreprise;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    public function getPrenomtuteurp(): string
    {
        return $this->prenomtuteurp;
    }

    public function setPrenomtuteurp(string $prenomtuteurp): void
    {
        $this->prenomtuteurp = $prenomtuteurp;
    }

    public function getFonctiontuteurp(): ?string
    {
        return $this->fonctiontuteurp;
    }

    public function setFonctiontuteurp(?string $fonctiontuteurp): void
    {
        $this->fonctiontuteurp = $fonctiontuteurp;
    }

    public function getIdentreprise(): int
    {
        return $this->identreprise;
    }

    public function setIdentreprise(int $identreprise): void
    {
        $this->identreprise = $identreprise;
    }

}