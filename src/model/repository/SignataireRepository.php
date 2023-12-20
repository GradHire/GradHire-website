<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\AbstractDataObject;
use app\src\model\dataObject\Signataire;

class SignataireRepository extends AbstractRepository
{
	/**
	 * @throws ServerErrorException
	 */
	public function getFullByEntreprise(int $identreprise): ?array
	{
		$sql = "SELECT * FROM Signataire WHERE idEntreprise = :idEntreprise";
		$result = self::FetchAllAssoc($sql, ['idEntreprise' => $identreprise]) ?? [];
		$signataires = [];
		foreach ($result as $row)
			$signataires[$row["nomsignataire"]] = $row["nomsignataire"];
		return $signataires;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getFullByEntrepriseNom(string $nomsignataire, int $idEntreprise): ?AbstractDataObject
	{
		$sql = "SELECT * FROM Signataire WHERE idEntreprise = :idEntreprise AND nomSignataire= :nomsignataire";
		$result = self::FetchAllAssoc($sql, ['idEntreprise' => $idEntreprise, 'nomsignataire' => $nomsignataire]);
		return $result ? $this->construireDepuisTableau($result[0]) : null;
	}

	protected function construireDepuisTableau(array $dataObjectFormatTableau): AbstractDataObject
	{
		return new Signataire(
			$dataObjectFormatTableau);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function create(mixed $nomSignataire, mixed $prenomSignataire, mixed $fonctionSignataire, mixed $mailSignataire, mixed $idEntreprise): void
	{
		self::Execute("SELECT creerSignataire(:nomSignataire, :prenomSignataire, :mailSignataire, :fonctionSignataire, :idEntreprise)", [
			"nomSignataire" => $nomSignataire,
			"prenomSignataire" => $prenomSignataire,
			"fonctionSignataire" => $fonctionSignataire,
			"mailSignataire" => $mailSignataire,
			"idEntreprise" => $idEntreprise
		]);
	}

	protected function getNomTable(): string
	{
		return "Signataire";
	}

	protected function getNomColonnes(): array
	{
		return ["idSignataire", "nomSignataire", "prenomSignataire", "fonctionSignataire", "mailSignataire", "idEntreprise"];
	}
}