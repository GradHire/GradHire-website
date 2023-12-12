<?php

namespace app\src\model\repository;

use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\CompteRendu;

class CompteRenduRepository extends AbstractRepository
{
	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfAlreadyCompteRendu(int $idtuteur, int $idetudiant, int $numconvention): bool
	{
		return self::Fetch(
				"SELECT idcompterendu FROM compterendus WHERE idtuteur = :idtuteur AND idetudiant = :idetudiant AND numconvention = :numconvention",
				['idtuteur' => $idtuteur, 'idetudiant' => $idetudiant, 'numconvention' => $numconvention]
			) !== null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getCommentaires(mixed $numConvention, int $studentId): ?array
	{
		return self::FetchAll(
			"SELECT * FROM compterendus WHERE numconvention = :numconvention AND idetudiant = :idetudiant",
			['numconvention' => $numConvention, 'idetudiant' => $studentId]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfCompteRenduExist(mixed $numconvention): bool
	{
		return self::Fetch("SELECT numconvention FROM compterendus WHERE numconvention = :numconvention", ['numconvention' => $numconvention]) !== null;
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function updateCompteRenduProf(mixed $numconvention, mixed $compteRendu): void
	{
		self::Execute(
			"UPDATE compterendus SET commentaireprof = :commentaireprof WHERE numconvention = :numconvention",
			['numconvention' => $numconvention, 'commentaireprof' => $compteRendu]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function updateCompteRenduEntreprise(mixed $numconvention, mixed $compteRendu): void
	{
		self::Execute(
			"UPDATE compterendus SET commentaireentreprise = :commentaireentreprise WHERE numconvention = :numconvention",
			['numconvention' => $numconvention, 'commentaireentreprise' => $compteRendu]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function createCompteRenduAsProf(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu): void
	{
		self::Execute(
			"INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentaireprof) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentaireprof)",
			[
				'idtuteurprof' => $idtuteurprof,
				'idtuteurentreprise' => $idtuteurentreprise,
				'idetudiant' => $idetudiant,
				'numconvention' => $numconvention,
				'commentaireprof' => $compteRendu
			]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function createCompteRenduAsEntreprise(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRendu): void
	{
		self::Execute(
			"INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentaireentreprise) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentaireentreprise)",
			[
				'idtuteurprof' => $idtuteurprof,
				'idtuteurentreprise' => $idtuteurentreprise,
				'idetudiant' => $idetudiant,
				'numconvention' => $numconvention,
				'commentaireentreprise' => $compteRendu
			]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function getIfImOneOfTheTutors(mixed $numconvention, mixed $idtuteur): ?array
	{
		return self::Fetch(
			"SELECT * FROM compterendus WHERE numconvention = :numconvention AND (idtuteurprof = :idtuteur OR idtuteurentreprise = :idtuteur)",
			['numconvention' => $numconvention, 'idtuteur' => $idtuteur]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfCompteRenduProfExist(int $getNumConvention): bool
	{
		return (bool)self::Fetch("SELECT commentaireprof FROM compterendus WHERE numconvention = :numconvention", ['numconvention' => $getNumConvention]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfCompteRenduEntrepriseExist(int $getNumConvention): bool
	{
		return (bool)self::Fetch("SELECT commentaireentreprise FROM compterendus WHERE numconvention = :numconvention", ['numconvention' => $getNumConvention]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfCompteRenduSoutenanceProfExist(int $getNumConvention): bool
	{
		return (bool)self::Fetch("SELECT commentairesoutenanceprof FROM compterendus WHERE numconvention = :numconvention", ['numconvention' => $getNumConvention]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfCompteRenduSoutenanceEntrepriseExist(int $getNumConvention): bool
	{
		return (bool)self::Fetch("SELECT commentairesoutenanceentreprise FROM compterendus WHERE numconvention = :numconvention", ['numconvention' => $getNumConvention]);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function checkIfCompteRenduSoutenanceExist(mixed $numconvention): bool
	{
		return (bool)self::Fetch(
			"SELECT commentairesoutenanceprof, commentairesoutenanceentreprise FROM compterendus WHERE numconvention = :numconvention",
			['numconvention' => $numconvention]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function updateCompteRenduSoutenanceProf(mixed $numconvention, mixed $compteRenduSoutenance): void
	{
		self::Execute(
			"UPDATE compterendus SET commentairesoutenanceprof = :commentairesoutenanceprof WHERE numconvention = :numconvention",
			['numconvention' => $numconvention, 'commentairesoutenanceprof' => $compteRenduSoutenance]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function createCompteRenduSoutenanceAsProf(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRenduSoutenance): void
	{
		self::Execute(
			"INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentairesoutenanceprof) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentairesoutenanceprof)",
			[
				'idtuteurprof' => $idtuteurprof,
				'idtuteurentreprise' => $idtuteurentreprise,
				'idetudiant' => $idetudiant,
				'numconvention' => $numconvention,
				'commentairesoutenanceprof' => $compteRenduSoutenance
			]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function updateCompteRenduSoutenanceEntreprise(mixed $numconvention, mixed $compteRenduSoutenance): void
	{
		self::Execute(
			"UPDATE compterendus SET commentairesoutenanceentreprise = :commentairesoutenanceentreprise WHERE numconvention = :numconvention",
			['numconvention' => $numconvention, 'commentairesoutenanceentreprise' => $compteRenduSoutenance]
		);
	}

	/**
	 * @throws ServerErrorException
	 */
	public static function createCompteRenduSoutenanceAsEntreprise(mixed $idtuteurprof, mixed $idtuteurentreprise, mixed $idetudiant, mixed $numconvention, mixed $compteRenduSoutenance): void
	{
		self::Execute(
			"INSERT INTO compterendus (idtuteurprof, idtuteurentreprise, idetudiant, numconvention, commentairesoutenanceentreprise) VALUES (:idtuteurprof, :idtuteurentreprise, :idetudiant, :numconvention, :commentairesoutenanceentreprise)",
			[
				'idtuteurprof' => $idtuteurprof,
				'idtuteurentreprise' => $idtuteurentreprise,
				'idetudiant' => $idetudiant,
				'numconvention' => $numconvention,
				'commentairesoutenanceentreprise' => $compteRenduSoutenance
			]
		);
	}

	/**
	 * @throws \Exception
	 */
	protected function construireDepuisTableau(array $dataObjectFormatTableau): CompteRendu
	{
		return new CompteRendu($dataObjectFormatTableau);
	}

	protected function getNomColonnes(): array
	{
		return [
			"numconvention",
			"idetudiant",
			"idtuteurprof",
			"commentaireprof",
			"idtuteurentreprise",
			"commentaireentreprise",
		];
	}

	protected function getNomTable(): string
	{
		return "compterendus";
	}
}