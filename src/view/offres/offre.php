<?php
/** @var $offre \app\src\model\dataObject\Offre
 * @var $utilisateurs array
 */


?>
<a href="/offres/<?php echo $offre->getIdoffre(); ?>"
   class="rounded-[10px] cursor-pointer group bg-white p-4 !pt-10 sm:p-6 shadow-sm hover:shadow min-w-[200px] shrink duration-150 border-2 border-zinc-200 hover:border-zinc-300">
    <?php $dateCreation = new DateTime($offre->getDatecreation());
    $dateCreation = $dateCreation->format('d/m/Y');
    echo "<p class=\"block text-xs text-zinc-500\">" . $dateCreation . "</p>";
    ?>
    <span>
        <span class="mt-0.5 text-lg font-medium text-zinc-900">
            <?php echo $offre->getSujet() ?>
        </span>
    </span>

    <div class="mt-4 flex w-full gap-1">
        <span class="whitespace-nowrap rounded-full bg-zinc-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-zinc-600">
           <?php
           $userId = $offre->getIdutilisateur();
           if (isset($utilisateurs[$userId])) echo $utilisateurs[$userId];
           else echo 'Utilisateur inconnu';
           ?>
        </span>
        <span class="whitespace-nowrap rounded-full bg-zinc-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-zinc-600">
        <?php echo $offre->getThematique() ?></span>
        <span class="group w-full pr-2 group-hover:pr-0 duration-150 inline-flex items-end justify-end gap-1 text-sm font-medium text-zinc-600"><span
                    aria-hidden="true"
                    class="block transition-all group-hover:ms-0.5 rtl:rotate-180">&rarr;</span></span>
    </div>
</a>