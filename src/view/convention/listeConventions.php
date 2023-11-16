<?php
/** @var $conventions ConventionRepository */

use app\src\core\components\Table;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;

$this->title = 'Conventions';

?>
<div class="overflow-x-auto w-full pt-12 pb-24">
	<?php
	$filteredConventions = [];
	foreach ($conventions as $convention) {
		if (Auth::get_user()->role() == Roles::Enterprise && (new OffresRepository())->getById($convention->getIdOffre())->getIdutilisateur() != Auth::get_user()->id) continue;
		elseif (Auth::get_user()->role() == Roles::Student && $convention->getIdUtilisateur() != Auth::get_user()->id) continue;
		else {
			$filteredConventions[] = $convention;
		}
	}


	Table::createTable($filteredConventions, ["Origine Convention", "Etudiant", "IdOffre", "Validité Entreprise", "Validité Pédagogique"], function ($convention) {
		Table::cell($convention->getOrigineConvention());
		Table::cell((new EtudiantRepository([]))->getByIdFull($convention->getIdUtilisateur())->getPrenom() . " " . (new EtudiantRepository([]))->getByIdFull($convention->getIdUtilisateur())->getNomutilisateur());
		Table::cell((new OffresRepository())->getById($convention->getIdOffre())->getSujet());
		if ($convention->getConventionValide() == "0") {
			Table::chip("Non valide", "yellow");
		} else if ($convention->getConventionValide() == "1") {
			Table::chip("Validée", "green");
		}
		if ($convention->getConvetionValideePedagogiquement() == "0") {
			Table::chip("Non valide", "yellow");
		} else if ($convention->getConvetionValideePedagogiquement() == "1") {
			Table::chip("Validée", "green");
		} if (Auth::has_role(Roles::Manager, Roles::Staff)) {
            if ($convention->getConvetionValideePedagogiquement() == "0")
                Table::button("/validateConventionPedagogiquement/" . $convention->getNumConvention(), "Valider");
            else
                Table::button("/unvalidateConventionPedagogiquement/" . $convention->getNumConvention(), "Invalider");
        } else if (Auth::has_role(Roles::Enterprise)) {
            if ($convention->getConventionValide() == "0" && $convention->getConvetionValideePedagogiquement() == "1")
                Table::button("/validateConvention/" . $convention->getNumConvention(), "Valider");
            else
                Table::button("/unvalidateConvention/" . $convention->getNumConvention(), "Invalider");
        }
		Table::button("/conventions/" . $convention->getNumConvention());
	});
	?>
</div>