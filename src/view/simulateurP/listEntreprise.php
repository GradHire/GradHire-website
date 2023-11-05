<?php
/** @var $listEntreprise ?array */
?>
<?php
if ($listEntreprise == null) {
    echo "<p class='text-center text-2xl'>Aucune entreprise n'a été trouvée</p>";
    //fais un bouton
    echo "<button type='button' id='revenir' class='text-center text-2xl'>Revenir à la recherche (ou créer l'entreprise)</button>";
} else {
    echo "TEST";
}
?>
<script>
    document.getElementById('revenir').addEventListener('click', function () {
        window.location.href = 'simulateurOffre';
    });
</script>