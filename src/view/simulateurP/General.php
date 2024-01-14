<?php
/* @var $vueChemin
 */

/* @var $nom */

use app\src\model\View;

$this->title = 'Simulateur';
View::setCurrentSection('Simulateur');

function getMenuElement($href, $label, $activeConditions, $vueChemin, $nom = null, $sessionKey = null): string
{
    $isActive = isset($nom)
        ? $activeConditions['vueChemin'] === $vueChemin && $activeConditions['nom'] === $nom
        : $activeConditions['vueChemin'] === $vueChemin;

    $color = match (true) {
        $isActive => 'text-zinc-500 bg-white shadow border font-bold">' . $label,
        isset($_SESSION[$sessionKey]) => ' text-blue-500">' . $label . '<a href="' . $href . '">' . '</a>',
        default => 'text-zinc-400 px-3 py-1">' . $label,
    };
    return '<div class="flex items-center justify-center text-sm text-nowrap"><span class="px-3 py-1 rounded-md ' . $color . '</span></div>';
}

$menuItems = [
    [
        'href' => 'explicationSimu',
        'label' => 'Explication',
        'activeConditions' => [
            'vueChemin' => 'explicationSimu.php',
            'nom' => ''
        ],
        'sessionKey' => 'simulateurExplication'
    ],
    [
        'href' => 'simulateur',
        'label' => 'Etudiant',
        'activeConditions' => [
            'vueChemin' => 'simulateur.php',
            'nom' => 'Etudiant'
        ],
        'sessionKey' => 'simulateurEtu',
    ],
    [
        'href' => 'listEntreprise',
        'label' => 'Entreprise',
        'activeConditions' => [
            'vueChemin' => 'listEntreprise.php',
            'nom' => ''
        ],
        'sessionKey' => 'idEntreprise',
    ],
    [
        'href' => 'simulateurServiceAccueil',
        'label' => 'Service d\'accueil',
        'activeConditions' => [
            'vueChemin' => 'simulateur.php',
            'nom' => 'Service'
        ],
        'sessionKey' => 'accueil',
    ],
    [
        'href' => 'simulateurTuteur',
        'label' => 'Tuteur',
        'activeConditions' => [
            'vueChemin' => 'listTiteur.php',
            'nom' => ''
        ],
        'sessionKey' => 'idTuteur',
    ],
    [
        'href' => 'simulateurCandidature',
        'label' => 'Stage',
        'activeConditions' => [
            'vueChemin' => 'simulateurCandidature.php',
            'nom' => ''
        ],
        'sessionKey' => 'simulateurCandidature',
    ],
    [
        'href' => 'simulateurProfReferent',
        'label' => 'Professeur référent',
        'activeConditions' => [
            'vueChemin' => '',
            'nom' => 'listProf.php'
        ],
        'sessionKey' => 'idProfRef',
    ],
    [
        'href' => 'simulateurSignataire',
        'label' => 'Signataire',
        'activeConditions' => [
            'vueChemin' => 'simulateur.php',
            'nom' => 'Signataire'
        ],
        'sessionKey' => 'signataire',
    ],
    [
        'href' => 'visuRecapConv',
        'label' => 'Récapitulatif',
        'activeConditions' => [
            'vueChemin' => 'visuRecapConv.php',
            'nom' => ''
        ]
    ],
];
?>
<div class="w-full flex items-center justify-center">
    <div class="h-10 items-center justify-start rounded-lg bg-zinc-100 p-1 w-fit flex overflow-x-scroll example border">
        <?php foreach ($menuItems as $item) {
            echo getMenuElement(
                $item['href'],
                $item['label'],
                $item['activeConditions'],
                $vueChemin,
                $nom ?? null,
                $item['sessionKey'] ?? null
            );
        } ?>
    </div>
</div>
<?php
require __DIR__ . "/{$vueChemin}";
?>
