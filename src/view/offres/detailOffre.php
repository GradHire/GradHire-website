
<?php
    use app\src\core\db\Database;
?>
<div class="flex flex-col">
    <div class="flex">
        <?php
        $sql = "SELECT * FROM entreprise e JOIN ComptePro c on c.idUtilisateur=e.idUtilisateur JOIN Utilisateur u on u.idUtilisateur=c.idUtilisateur WHERE e.idUtilisateur = " . $offre->getIdUtilisateur();
        $result = Database::get_conn()->query($sql);
        $row = $result->fetch();
        echo $row["nomUtilisateur"];
        ?>
    </div>

    <div class="flex">
        <?php  echo $offre->getSujet(); ?>
    </div>
</div>
