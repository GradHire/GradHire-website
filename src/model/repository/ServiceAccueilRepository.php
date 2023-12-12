<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\ServiceAccueil;

class ServiceAccueilRepository extends AbstractRepository
{

	/**
	 * @throws ServerErrorException
	 */
	public function getFullByEntreprise(int $identreprise): ?array
	{
		$result = self::FetchAllAssoc("SELECT * FROM ServiceAccueil WHERE idEntreprise = :idEntreprise", [":idEntreprise" => $identreprise]);
		$serviceAccueil = [];
		foreach ($result as $row)
			$serviceAccueil[$row['nomservice']] = $row['nomservice'];
		return $serviceAccueil;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function create(mixed $nomService, mixed $idEntreprise, mixed $voie, mixed $residence, mixed $cp, mixed $ville, mixed $pays): void
	{
		self::Execute("CALL creerServiceAccueil(:nomService,:residence, :voie,:cedex, :cp, :ville, :pays,:idEntreprise)", [
			"nomService" => $nomService,
			"idEntreprise" => $idEntreprise,
			"voie" => $voie,
			"residence" => $residence,
			"cp" => $cp,
			"ville" => $ville,
			"pays" => $pays,
			"cedex" => ""
		]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getFullByEntrepriseNom(int $identreprise, string $nomService): ?ServiceAccueil
	{
		$result = self::FetchAssoc("SELECT * FROM ServiceAccueil WHERE idEntreprise = :idEntreprise AND nomService = :nomService", [
			":idEntreprise" => $identreprise,
			":nomService" => $nomService
		]);
		return $result ? $this->construireDepuisTableau($result) : null;
	}

	protected function construireDepuisTableau(array $dataObjectFormatTableau): ServiceAccueil
	{
		return new ServiceAccueil(
			$dataObjectFormatTableau
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getCodePostal(int $idEntreprise, string $nomService): ?string
	{
		$idVille = $this->idVille($idEntreprise, $nomService);
		$result = self::FetchAssoc("SELECT codePostal FROM Ville WHERE idVille = :idVille", [":idVille" => $idVille]);
		return $result ? $result['codepostal'] : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function idVille(int $idEntreprise, string $nomService): ?string
	{
		$result = self::FetchAssoc("SELECT idVille FROM ServiceAccueil WHERE idEntreprise = :idEntreprise AND nomService = :nomService", [
			":idEntreprise" => $idEntreprise,
			":nomService" => $nomService
		]);
		return $result ? $result['idville'] : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getCommune(int $idEntreprise, string $nomService): ?string
	{
		$idVille = $this->idVille($idEntreprise, $nomService);
		$result = self::FetchAssoc("SELECT nomVille FROM Ville WHERE idVille = :idVille", [":idVille" => $idVille]);
		return $result ? $result['nomville'] : null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function getPays(int $idEntreprise, string $nomService): ?string
	{
		$idVille = $this->idVille($idEntreprise, $nomService);
		$result = self::FetchAssoc("SELECT pays FROM Ville WHERE idVille = :idVille", [":idVille" => $idVille]);
		return $result ? $result['pays'] : null;
	}

	protected function getNomTable(): string
	{
		return "ServiceAccueil";
	}

	protected function getNomColonnes(): array
	{
		return [
			"idService",
			"nomService",
			"adresse",
			"adresseCedex",
			"adresseResidence",
			"idVille",
			"idEntreprise"
		];
	}
}