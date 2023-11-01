<?php

namespace app\src\model\dataObject;
enum Roles: string
{
    case Student = 'etudiant';
    case Teacher = 'enseignant';
    case Staff = "secretariat";
    case Manager = "responsable";
    case ChefDepartment = "chefdepartement";
    case ManagerStage = "responsablestage";
    case ManagerAlternance = "responsablealternance";
    case Enterprise = "entreprise";
    case Tutor = "tuteur";
}