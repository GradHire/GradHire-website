<?php

/** @var $entreprise \app\src\model\dataObject\Entreprise
 * @var $offres \app\src\model\dataObject\Offre
 */

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Users\Roles;


?>
<div class="w-full pt-12 pb-24">
	<div class="w-full flex md:flex-row flex-col  justify-between items-start">
		<div class="px-4 sm:px-0">
			<h3 class="text-lg font-semibold leading-7 text-zinc-900"><?= $entreprise->getNomutilisateur() ?></h3>
			<p class="mt-1 max-w-2xl text-sm leading-6 text-zinc-500"><?= $entreprise->getTypestructure() ?></p>
		</div>
	</div>
	<div class="mt-6 border-t border-zinc-100">
		<dl class="divide-y divide-zinc-100">
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Effectif</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$effectif = $entreprise->getEffectif();
					if ($effectif != null) echo $effectif;
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Code NAF</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$codeNaf = $entreprise->getCodenaf();
					if ($codeNaf != null) echo $codeNaf;
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Fax</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$fax = $entreprise->getFax();
					if ($fax != null) echo $fax;
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Site web</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$siteWeb = $entreprise->getSiteweb();
					if ($siteWeb != null) echo "<a target=\"_blank\" href=\"" . $siteWeb . "\">" . $siteWeb . "</a>";
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Numéro de téléphone</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$numTel = $entreprise->getNumtelutilisateur();
					if ($numTel != null) echo $numTel;
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Email</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$email = $entreprise->getEmailutilisateur();
					if ($email != null) echo "<a href=\"mailto:" . $email . "\">" . $email . "</a>";
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">SIRET</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$siret = $entreprise->getSiret();
					if ($siret != null) echo $siret;
					else echo("Non renseigné");
					?></dd>
			</div>
			<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
				<dt class="text-sm font-medium leading-6 text-zinc-900">Statut juridique</dt>
				<dd class="mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0">
					<?php
					$statutJuridique = $entreprise->getStatutjuridique();
					if ($statutJuridique != null) echo $statutJuridique;
					else echo("Non renseigné");
					?></dd>
			</div>
			<?php if ($offres != null) { ?>
				<div class="px-4 py-6 sm:gap-4 sm:px-0">
				<div class="w-full">
				<table class="w-full divide-y-2 divide-zinc-200 bg-white text-sm">
				<thead class="ltr:text-left rtl:text-right">
				<tr>
					<th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
						Sujet
					</th>
					<th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
						Thématique
					</th>
					<th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
						Date de création
					</th>
					<th class="whitespace-nowrap px-4 py-2 font-medium text-left text-zinc-900">
						Statut
					</th>
				</tr>
				</thead>

				<tbody class="divide-y divide-zinc-200">
				<?php
				if ($offres != null) {
					foreach ($offres as $offre) {
						if (Auth::has_role(Roles::Student) && ($offre->getStatut() != "approved" || Application::getUser()->attributes()["annee"] == 3 && $offre->getAnneeVisee() == 2)) continue;
						?>
						<tr class="odd:bg-zinc-50">
							<td class="whitespace-nowrap px-4 py-2 font-medium text-zinc-900">
								<?= $offre->getSujet(); ?>
							</td>
							<td class="whitespace-nowrap px-4 py-2 text-zinc-700">
								<?php
								$thematique = $offre->getThematique();
								if ($thematique != null) echo $thematique;
								else echo("Non renseigné");
								?>
							</td>
							<td class="whitespace-nowrap px-4 py-2 text-zinc-700">
								<?php
								$dateCreation = new DateTime($offre->getDatecreation());
								$dateCreation = $dateCreation->format('d/m/Y H:i:s');
								echo $dateCreation;
								?>
							</td>
							<td class="whitespace-nowrap px-4 py-2 text-zinc-700">
								<?php
								if ($offre->getStatut() == "pending") {
									echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-yellow-100 text-yellow-800\">
    En attente
</span>";
								} else if ($offre->getStatut() == "approved") {
									echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800\">
    Validée
    </span>";
								} else if ($offre->getStatut() == "blocked") {
									echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-red-100 text-red-800\">
    Refusée
    </span>";
								} else if ($offre->getStatut() == "draft") {
									echo "<span class=\"inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-zinc-100 text-zinc-800\">
    Archivée
    </span>";
								}
								?>
							</td>
							<td class="whitespace-nowrap px-4 py-2">
								<a href="/offres/<?= $offre->getId() ?>"
								   class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
								                                                                                                       plus</a>
							</td>
						</tr>
						<?php
					}
					?>
					</tbody>
					</table>
					</div>
					</div>
				<?php }
			} ?>
		</dl>
	</div>
</div>
