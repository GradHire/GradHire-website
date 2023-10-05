<?php

namespace app\src\model\Users;


class StudentUser extends LdapUser
{
    protected static string $view = "EtudiantVue";
    protected static string $create_function = "creerEtu";

    public function role(): string
    {
        return 'student';
    }
}