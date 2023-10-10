<?php

/** @var $candidatures \app\src\model\dataObject\Candidature */

echo "<div class='flex flex-col'> ";
$filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/cv.pdf";
echo "<a href='".$filepath."' download target='_blank'>📥 Téléchargez le CV </a>";

$filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/ltm.pdf";
echo "<a href='".$filepath."' download target='_blank'>📥 Téléchargez la Lettre de Motivation</a>
</div>";


?>