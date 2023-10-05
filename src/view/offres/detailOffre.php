<?php
use app\src\core\db\Database;

/** @var $offre \app\src\model\dataObject\Offre */

?>
<div class="flex flex-col">
    <div class="flex">
        <?php
        if($offre !== null) {
            $sql = "SELECT * FROM Entreprise e JOIN Comptepro c on c.idUtilisateur=e.idUtilisateur JOIN Utilisateur u on u.idutilisateur=c.idutilisateur WHERE e.idutilisateur = " . $offre->getIdUtilisateur();
            $result = Database::get_conn()->query($sql);
            $row = $result->fetch();
            echo $row["nomutilisateur"];
        }
        ?>
    </div>

    <div class="flex">
        <?php
        if($offre !== null) {
            echo $offre->getSujet();
        }
        ?>
    </div>
</div>
