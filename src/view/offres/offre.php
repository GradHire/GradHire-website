<?php
/** @var $offre \app\src\model\dataObject\Offre */

?>
    <a href="detailOffre?idoffre=<?php echo $offre->getIdOffre(); ?>"
       class="rounded-[10px] cursor-pointer group bg-white p-4 !pt-10 sm:p-6 shadow-lg hover:shadow-sm w-full max-w-[220px] duration-150 border-2 border-gray-200 hover:border-gray-300">
        <?php echo sprintf('<time datetime="%s" class="block text-xs text-gray-500">
    </time>', $offre->getDateDebut()) ?>
        <span>
        <h3 class="mt-0.5 text-lg font-medium text-gray-900">
            <?php echo $offre->getSujet() ?>
        </h3>
    </span>

        <div class="mt-4 flex w-full gap-1">
        <span class="whitespace-nowrap rounded-full bg-gray-100 px-2.5 py-0.5 text-center flex justify-center items-center text-xs text-gray-600">
        <?php echo $offre->getThematique() ?></span>
            <span class="group w-full pr-2 group-hover:pr-0 duration-150 inline-flex items-end justify-end gap-1 text-sm font-medium text-gray-600"><span
                        aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">&rarr;</span></span>
        </div>
    </a>