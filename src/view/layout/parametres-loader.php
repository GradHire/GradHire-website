<?php
/** @var array $allSections
 * @var array $allActions
 * @var array $parametres
 */

use app\src\core\exception\ServerErrorException;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;

/** @var array $allSections
 * @var array $allActions
 * @var array $parametres
 */

require_once __DIR__ . "/sidebar.php";

$sections = ['S01'];
$actions = [];

function getRoleBasedData($role, &$sections, &$actions): array
{
    match ($role) {
        Roles::Student => [
            $selectedIds = ['S01', 'S05', 'S09', 'S08'],
            $actionsId = ['A01', 'A02', 'A03'],
            $sections[] = 'S05',
            $actions[] = 'A03'
        ],
        Roles::Tutor, Roles::Teacher => [
            $selectedIds = ['S01', 'S05'],
            $actionsId = ['A01', 'A02', 'A03'],
            $sections[] = 'S05'
        ],
        Roles::Enterprise => [
            $selectedIds = ['S05', 'S06'],
            $actionsId = ['A01', 'A02', 'A03'],
            array_push($sections, 'S01', 'S06', 'S08')
        ],
        Roles::Staff, Roles::Manager => [
            $selectedIds = ['S01', 'S03', 'S05', 'S06', 'S07', 'S08'],
            $actionsId = ['A01', 'A02', 'A04', 'A05'],
            array_push($sections, 'S03', 'S05', 'S06', 'S07', 'S08')
        ],
        Roles::ChefDepartment => [
            $selectedIds = ['S01', 'S02', 'S03', 'S04', 'S05', 'S06', 'S07', 'S08', 'S10'],
            $actionsId = ['A01', 'A02', 'A04', 'A05'],
            array_push($sections, 'S01', 'S02', 'S03', 'S04', 'S05', 'S06', 'S07', 'S08', 'S10')
        ],
        default => [
            $selectedIds = [],
            $actionsId = []
        ],
    };
    return [$selectedIds, $actionsId];
}

function filterArraysByKey($selectedIds, $actionsId, &$allSections, &$allActions): void
{
    foreach ($allSections as $key => $value) if (!in_array($key, $selectedIds)) unset($allSections[$key]);
    foreach ($allActions as $key => $value) if (!in_array($key, $actionsId)) unset($allActions[$key]);
}

function getParametersAndDefaultData($sections, $actions): array
{
    $parametres = [
        'sections' => array_unique($sections),
        'actions' => $actions,
    ];
    $parametresContent = json_encode($parametres);
    return [
        'parametres' => $parametresContent,
        'defaultSections' => $parametres['sections'] ?? [],
        'defaultActions' => $parametres['actions'] ?? []
    ];
}

try {
    $currentRole = Auth::get_user()->role();
} catch (ServerErrorException $e) {
    $currentRole = Roles::Student;
}

[$selectedIds, $actionsId] = getRoleBasedData($currentRole, $sections, $actions);
$selectedIds = array_merge($selectedIds, ['S11', 'S12']);

filterArraysByKey($selectedIds, $actionsId, $allSections, $allActions);
$data = getParametersAndDefaultData($sections, $actions);

$sections = $_SESSION['parametres']['sections'] ?? $data['defaultSections'];
$actions = $_SESSION['parametres']['actions'] ?? $data['defaultActions'];
