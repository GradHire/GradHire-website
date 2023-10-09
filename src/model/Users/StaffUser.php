<?php

namespace app\src\model\Users;

class StaffUser extends LdapUser
{
	protected static string $view = "StaffVue";

	public function role(): Roles
	{
		return Roles::tryFrom($this->attributes["role"]);
	}
}