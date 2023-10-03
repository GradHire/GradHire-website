

<?php
/** @var $offres \app\src\model\Offre[] */

echo '<p> liste des offres : </p>';

foreach ($offres as $offre)
  echo '<p> nom de l\'offre : '. $offre->getSujet() . '</p>';
?>