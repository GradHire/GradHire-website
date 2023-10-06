<?php

namespace app\src\model\Users;

class TutorUser extends ProUser
{
    protected static string $view = "TuteurVue";

    public function role(): string
    {
        return Roles::Tutor->value;
    }

    public function full_name(): string
    {
        return $this->attributes["prenomtuteurp"] . " " . $this->attributes["nomutilisateur"];
    }
}