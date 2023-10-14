<?php
/** @var $candidatures \app\src\model\dataObject\Candidature */
/** @var $enAttente */
echo'test';
use app\src\model\dataObject\Entreprise;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\UtilisateurRepository;
                        foreach ($candidatures as $candidature) {
                            if(($candidature->getEtatcandidature()=='on hold')==$enAttente){

                                $entreprise=(new EntrepriseRepository())->getByIdFull($candidature->getIdcandidature());
                                $offre=(new OffresRepository())->getById($candidature->getIdoffre());
                                $etudiant=(new EtudiantRepository())->getUserById($candidature->getIdtilisateur);
                                ?>
                                <tr class="odd:bg-gray-50">
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        <?= $entreprise->getNomutilisateur(); ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        <?php echo $offre->getSujet(); ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                        <?php
                                        echo $etudiant->getEmailutilisateur();
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                        <?php
                                        echo $candidature->getDatec();
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                        <?php
                                        echo $candidature->getEtatcandidature();
                                        ?>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <a href="/candidatures/<?php echo $candidature["idcandidature"] ?>"
                                           class="inline-block rounded bg-zinc-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700">Voir
                                            plus</a>
                                    </td>
                                </tr>
                            <?php }} ?>