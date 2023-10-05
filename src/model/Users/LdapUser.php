<?php

namespace app\src\model\Users;


class LdapUser extends User
{
    protected static string $id_attributes = "loginldap";

    public function full_name(): string
    {
        return $this->attributes['prenomutilisateurldap'] . " " . $this->attributes['nomutilisateur'];
    }
}