<?php
/** @var array $allSections
 * @var array $allActions
 * @var array $parametres
 */

use app\src\model\dataObject\Roles;

$sections = ['S01'];
$actions = [];

require_once __DIR__ . "/sidebar.php";

$roles = Roles::cases();
foreach ($roles as $role) {
    switch ($role) {
        case Roles::Student:
            $sections[] = 'S05';
            $actions[] = 'A03';
            break;
        case Roles::Tutor:
        case Roles::Teacher:
            $sections[] = 'S05';
            break;
        case Roles::Enterprise:
            array_push($sections, 'S01', 'S06', 'S08');
            break;
        case Roles::Manager:
        case Roles::Staff:
            array_push($sections, 'S03', 'S05', 'S06', 'S07', 'S08');
            break;
        default:
            break;
    }
}

$sections = array_unique($sections);

$parametres = [
    'sections' => $sections,
    'actions' => $actions,
];

$parametresContent = json_encode($parametres);
$defaultSections = $parametres['sections'] ?? [];
$defaultActions = $parametres['actions'] ?? [];
$sections = $_SESSION['parametres']['sections'] ?? $defaultSections;
$actions = $_SESSION['parametres']['actions'] ?? $defaultActions;

