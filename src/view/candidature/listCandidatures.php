<?php
/** @var $candidaturesAttente \app\src\model\dataObject\Postuler */

/** @var $candidaturesAutres \app\src\model\dataObject\Postuler */

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;

?>
<div class="w-full grid-cols-1 gap-4 lg:grid-cols-4 ">
    <div class="w-full lg:col-span-3 rounded-lg flex flex-col gap-4">

        <?php
        if (isset($error)) {
            echo '<div class="w-full bg-red-200 rounded-lg p-4 text-red-700">' . $error . '</div>';

        }
        if (isset($success)) {
            echo '<div class="w-full bg-green-200 rounded-lg p-4 text-green-700">' . $success . '</div>';
        }
        if (!empty($candidaturesAttente)) {
            $candidatures = $candidaturesAttente;
            $titre = 'Candidatures en Attente';
            require __DIR__ . '/candidature.php';
        }
        ?>

        <div class="w-full bg-zinc-200 h-[1px] rounded-full"></div>

        <?php if (!empty($candidaturesAutres)) {
            $candidatures = $candidaturesAutres;
            $titre = 'Candidatures Traitées';
            require __DIR__ . '/candidature.php';
        }

        if (empty($candidaturesAttente) && empty($candidaturesAutres) && !isset($error) && !isset($success)) {
            if (Auth::has_role(Roles::Tutor, Roles::Teacher)) echo "<h2>Aucune offre ne cherche de tuteur pour l'instant.</h2>";
            else if (Auth::has_role(Roles::Enterprise)) echo "<h2>Aucun étudiant n'a postuler sur une de vos offre.</h2";
            else echo "<h2>Vous n'avez postuler à aucune offre pour l'instant.</h2>";
        }
        ?>

    </div>
</div>
