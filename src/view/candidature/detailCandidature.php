<?php

/** @var $candidatures \app\src\model\dataObject\Candidature */

echo "<div class='flex flex-col'>
<div class='flex flex-col mb-3'> ";
$filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/cv.pdf";
echo "<a href='".$filepath."' download target='_blank'>📥 Téléchargez le CV </a>";

$filepath = "/uploads/". $candidatures->getIdoffre()."_".$candidatures->getIdutilisateur()."/ltm.pdf";
echo "<a href='".$filepath."' download target='_blank'>📥 Téléchargez la Lettre de Motivation</a>
</div>";

?>

<form action="/candidatures" method="POST">
    <input type="hidden" name="idcandidature" value="<?php echo $candidatures->getIdcandidature() ?>">
    <button type='submit' name='action' value='Accepter'>Accepter</button>
    <button type='submit' name='action' value='Refuser'>Réfuser</button>
</form>
</div>

