<?php

namespace app\src\model\Users;
enum Roles: string
{
    case Student = 'etudiant';
    case Teacher = 'enseignant';
    case Staff = "secretariat";
    case Manager = "responsable";
    case Enterprise = "entreprise";
    case Tutor = "tuteur";
}