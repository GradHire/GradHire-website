<?php
/** @var $soutenance Soutenance */

/** @var $form FormModel */

use app\src\model\dataObject\Soutenance;
use app\src\model\Form\FormModel;

?>
<div id="step1">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Consignes Générales</h2>


        <p class="mb-4">Un stage débuté en retard n’est en aucune manière une circonstance atténuante pour
            des résultats moindres. Il est complètement logique qu’un stage de dernière minute, non rémunéré, en
            distanciel
            ou débuté en retard obtienne une note moindre.</p>
        <p class="mb-4">Soyez juste (dur quand nécessaire) dans votre notation.Ne pas vous souciez, svp, de si la note
            de
            stage permettra à l’étudiant de passer en BUT3, il y aura un jury pour ça.</p>

        <div class="mb-4">
            <h3 class="font-bold">Échelle de notes:</h3>
            <ul class="list-disc ml-6">
                <li>Travail faible : note entre 6 et 10</li>
                <li>Travail tout juste: 10</li>
                <li>Assez bon: 12</li>
                <li>Bon: 14</li>
                <li>Très bon : 16 (doit rester exceptionnel)</li>
            </ul>
        </div>
    </div>
    <?php $form->start(); ?>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col" id="step1">
            <?php
            $form->print_fields(["etudiant", "presenttuteur"]);
            ?>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step1', 'step2')">Suivant</button>
        </div>
    </div>
</div>
<div class="hidden" id="step2">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Le rapport</h2>

        <div class="mb-4">
            <h3 class="font-bold">La note du rapport comprend :</h3>
            <ul class="list-disc ml-6">
                <li>le respect des consignes de forme (s'appuyer sur la grille de relecture)</li>
                <li>la qualité de rédaction: tournures de phrases / fautes d'orthographe</li>
                <li>l'effort de pédagogie</li>
                <li>l'utilisation de schémas et/ou diagrammes: 1) un diagramme d'architecture au lieu d'une liste
                    d'outils/langages 2) un diagramme de sequence pour illustrer un processus plutôt qu'un long texte
                    ...
                </li>
            </ul>
        </div>
        <p class="mb-4">Le rapport intermédiaire n’est pas noté, mais si l’étudiant n’a pas tenu compte de vos remarques
            sur
            celui-ci lors du rendu final, il faut pénaliser.</p>
    </div>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col">
            <?php
            $form->print_fields(["renduretard", "noterapport", "commentairerapport"]);
            ?>
            <button type="button" class="bg-zinc-500 rounded-lg p-2" onclick="prevStep('step2', 'step1')">Précédent</button>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step2', 'step3')">Suivant</button>
        </div>
    </div>
</div>
<div class="hidden" id="step3">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Oral</h2>

        <div class="mb-4">
            <ul class="list-disc ml-6">
                <li>Durée respectée (20 min diapo + 10 min démo)</li>
                <li>Posture : parle distinctement / regarde l'auditoire</li>
                <li>Support avec transparents lisibles et numérotés avec figures / schémas (pas trop chargés en texte)
                </li>
            </ul>
        </div>
    </div>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col">
            <?php
            $form->print_fields(["noteoral", "commentaireoral"]);
            ?>
            <button type="button" onclick="prevStep('step3', 'step2')" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white">Précédent</button>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step3', 'step4')">Suivant</button>
        </div>
    </div>
</div>
<div class="hidden" id="step4">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Relations interpersonnelles</h2>

        <div class="mb-4">
            <h3 class="font-bold">La note comprend:</h3>
            <ul class="list-disc ml-6">
                <li>ponctualité / correction de l'étudiant</li>
                <li>intégration à l'équipe</li>
                <li>niveau de sollicitation adapté (ne reste pas dans son coin bloqué sur un pb / n’interrompt pas son
                    tuteur toutes les 5 minutes au moindre pb rencontré)
                </li>
            </ul>
        </div>
        <p class="mb-4">Cette note est celle du tuteur. Si le tuteur est complètement satisfait, on peut aller à 16</p>
    </div>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col">
            <?php
            $form->print_fields(["noterelation"]);
            ?>
            <button type="button" onclick="prevStep('step4', 'step3')" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white">Précédent</button>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step4', 'step5')">Suivant</button>
        </div>
    </div>
</div>
<div class="hidden" id="step5">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Démarche</h2>
        <p class="mb-4">Les étudiants ont pour consigne de présenter 2 à 3 difficultés techniques et de les rapprocher
            des compétences. Cette note sera utilisée pour le portfolio.</p>
        <div class="mb-4">
            <h3 class="font-bold">La note de la démarche comprend :</h3>
            <ul class="list-disc ml-6">
                <li>la capacité à appréhender de nouvelles technologies</li>
                <li>la capacité à restituer / expliquer ces technologies (diagrammes par exemple). L'étudiant n'est pas
                    juste utilisateur, mais il a compris / sait positionner ces technologies / les rapprocher/comparer
                    de ce qui a été étudié à l'IUT.
                </li>
            </ul>
        </div>
    </div>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col">
            <?php
            $form->print_fields(["langage", "nouveau", "difficulte", "notedemarche"]);
            ?>
            <button type="button" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white" onclick="prevStep('step5', 'step4')">Précédent</button>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step5', 'step6')">Suivant</button>
        </div>
    </div>
</div>
<div class="hidden" id="step6">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Résultat</h2>
        <div class="mb-4">
            <h3 class="font-bold">La note du résultat comprend:</h3>
            <ul class="list-disc ml-6">
                <li>Quantité du travail</li>
                <li>Qualité (code review / mis en production...)</li>
                <li>Une démo bien préparée (ex: base de données conséquente) construite avec un scénario qui illustre
                    bien l'utilisation du logiciel
                </li>
                <li>Il doit être en relation avec la difficulté du sujet. Exemple : pas plus de 10 si pas de
                    programmation.
                </li>
            </ul>
        </div>
    </div>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col">
            <?php
            $form->print_fields(["noteresultat", "commentaireresultat"]);
            ?>
            <button type="button" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white" onclick="prevStep('step6', 'step5')">Précédent</button>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-2 text-white" onclick="nextStep('step6', 'step7')">Suivant</button>
        </div>
    </div>
</div>
<div class="hidden" id="step7">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Possibilité d'alternances BUT3</h2>
        <p class="mb-4">Faire la pub sur le BUT3
            https://drive.google.com/drive/folders/1yR-CptCQv-Bd9IfI4fzp9B0NsaCN3qpJ</p>
    </div>
    <div class="w-full max-w-md gap-4 flex flex-col gap-4 mx-auto ">
        <div class="w-full gap-4 flex flex-col">
            <?php
            $form->print_fields(["recherche", "recontact"]);
            ?>
            <button type="button" class="bg-zinc-500 hover:bg-zinc-600 rounded-lg p-2 text-white" onclick="prevStep('step7', 'step6')">Précédent</button>
            <?php
            $form->submit("Envoyer");
            ?>
        </div>
    </div>
</div>
<script src="../resources/js/stepsForm.js"></script>

