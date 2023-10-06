<?php

namespace app\src\model\Users;

class ProUser extends User
{
    protected static string $id_attributes = "emailutilisateur";

    public function full_name(): string
    {
        return $this->attributes["nomutilisateur"];
    }
}