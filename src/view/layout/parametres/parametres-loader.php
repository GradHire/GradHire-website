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
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S08', 'S09', 'S10'],
            $actionsId = ['A01', 'A02', 'A03', 'A07'],
        ],
        Roles::Teacher => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S01', 'S10'],
            $actionsId = ['A01', 'A02', 'A07'],
        ],
        Roles::Staff => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S01', 'S10', 'S03', 'S07', 'S06'],
            $actionsId = ['A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07'],
        ],
        Roles::Manager => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S01', 'S10', 'S08', 'S03', 'S07', 'S06'],
            $actionsId = ['A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07'],
        ],
        Roles::ChefDepartment => [
            $selectedIds = ['S01', 'S03', 'S08', 'S11', 'S12', 'S13', 'S14'],
            $actionsId = ['A07'],
        ],
        Roles::ManagerStage => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S01', 'S10', 'S03', 'S07', 'S06'],
            $actionsId = ['A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07'],
        ],
        Roles::ManagerAlternance => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S01', 'S10', 'S03', 'S07', 'S06'],
            $actionsId = ['A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07'],
        ],
        Roles::Enterprise => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S06', 'S08', 'S10'],
            $actionsId = ['A04', 'A07'],
        ],
        Roles::Tutor => [
            $selectedIds = ['S02', 'S05', 'S13', 'S14', 'S10'],
            $actionsId = ['A01', 'A02', 'A07'],
        ],
        Roles::TutorTeacher => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14', 'S10'],
            $actionsId = ['A01', 'A02', 'A07'],
        ],
        default => [
            $selectedIds = ['S02', 'S04', 'S05', 'S13', 'S14'],
            $actionsId = ['A07'],
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
