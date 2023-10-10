<?php

namespace app\src\model\Users;


use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;

class StudentUser extends LdapUser
{
	protected static string $view = "EtudiantVue";
	protected static string $create_function = "creerEtu";
	protected static string $update_function = "updateEtudiant";

	public function role(): Roles
	{
		return Roles::Student;
	}

	/**
	 * @throws ServerErrorException
	 */
	public function update_year(string $new_year): void
	{
		try {
			$statement = Database::get_conn()->prepare("UPDATE `EtudiantVue` SET `annee`=? WHERE idutilisateur=?");
			$statement->execute([$new_year, $this->id]);
		} catch (\Exception) {
			throw new ServerErrorException();
		}
	}
}