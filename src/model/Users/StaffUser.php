<?php

namespace app\src\model\Users;

class StaffUser extends LdapUser
{
    protected static string $view = "StaffVue";
    protected static string $create_function = "creerStaff";
    protected static string $update_function = "updateStaff";

    public function role(): Roles
    {
        return Roles::tryFrom($this->attributes["role"]);
    }
}