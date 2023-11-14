<?php
/** @var $candidatures \app\src\model\dataObject\Postuler[] */

/** @var $titre string */

use app\src\core\components\Table;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;

?>
<div class="flex flex-col gap-1 w-full pt-12 pb-24">
	<h2 class="font-bold text-lg"><?php echo $titre ?></h2>
	<?php
	Table::createTable($candidatures, ["Nom de l'entreprise", "Sujet de l'offre", "Email étudiant", "Dates de candidature", "Etat de la candidature"], function ($candidature) {
		$offre = (new OffresRepository())->getById($candidature->getIdOffre());
		$entreprise = (new UtilisateurRepository([]))->getUserById($offre->getIdutilisateur());
		$etudiant = (new UtilisateurRepository([]))->getUserById($candidature->getIdUtilisateur());
		Table::cell($entreprise->getNomutilisateur());
		Table::cell($offre->getSujet());
		Table::cell($etudiant->getEmailutilisateur());
		Table::cell($candidature->getDates());
		if ($candidature->getStatut() == 'en attente')
			Table::chip("En attente", "yellow");
		elseif ($candidature->getStatut() == 'refuser')
			Table::chip("Refusé", "red");
		else
			Table::chip("Accepté", "green");
		Table::button("/candidatures/" . $candidature->getIdOffre() . "/" . $candidature->getIdUtilisateur());
	});
	?>
</div>