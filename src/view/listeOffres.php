<?php

echo '<div><form method="post" action="search">
    <input type="text" name="query" placeholder="Search...">
    <input type="checkbox" name="filter" value="false">BUT2
    <input type="checkbox" name="filter" value="false">BUT3
    <input type="submit" value="search">
    </form></div>';
echo '<div><p> liste des offres : </p></div>';

foreach ($offres as $offre)
    echo '<div></div><p> nom de l\'offre : '. $offre->getSujet() . '</p></div>';
?>