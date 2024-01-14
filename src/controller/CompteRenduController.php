<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\CompteRenduRepository;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\Request;

class CompteRenduController extends AbstractController
{

    /**
     * @throws ServerErrorException
     */
    public function compteRendu(Request $resquest): string
    {
        if (Auth::has_role(Roles::Tutor, Roles::TutorTeacher)) {
            $numConvention = $resquest->getRouteParam("numconvention");
            $informations = ConventionRepository::getInformationByNumConvention($numConvention);
            if (Auth::has_role(Roles::TutorTeacher)) {
                $form = new FormModel([
                    "idTuteurProf" => Application::getUser()->id(),
                    "idEtudiant" => $informations['idetudiant'],
                    "numConvention" => $numConvention,
                    'compteRendu' => FormModel::string("Compte rendu du tuteur professeure")->required(),
                ]);
            } else {
                $form = new FormModel([
                    "idTuteurEntreprise" => Application::getUser()->id(),
                    "idEtudiant" => $informations['idetudiant'],
                    "numConvention" => $numConvention,
                    'compteRendu' => FormModel::string("Compte rendu du tuteur entreprise")->required(),
                ]);
            }
            if ($resquest->getMethod() == "post") {
                if ($form->validate($resquest->getBody())) {
                    $data = $form->getParsedBody();
                    if (CompteRenduRepository::checkIfCompteRenduExist($informations['numconvention'])) {
                        if (Auth::has_role(Roles::TutorTeacher)) {
                            CompteRenduRepository::updateCompteRenduProf($informations['numconvention'], $data['compteRendu']);
                            NotificationRepository::createNotification($informations['idtuteurentreprise'], "Le tuteur professeur a changé le compte rendu de la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idetudiant'], "Le tuteur professeur a changé le compte rendu de votre convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idtuteurprof'], "Vous avez changé le compte rendu de la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                           }
                        else {
                            CompteRenduRepository::updateCompteRenduEntreprise($informations['numconvention'], $data['compteRendu']);
                            NotificationRepository::createNotification($informations['idtuteurprof'], "Le tuteur entreprise a changé le compte rendu de la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idetudiant'], "Le tuteur entreprise a changé le compte rendu de votre convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idtuteurentreprise'], "Vous avez changé le compte rendu de la convention" . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                        }
                    } else
                        if (Auth::has_role(Roles::TutorTeacher)) {
                            CompteRenduRepository::createCompteRenduAsProf($informations['idtuteurprof'], $informations['idtuteurentreprise'], $informations['idetudiant'], $informations['numconvention'], $data['compteRendu']);
                            NotificationRepository::createNotification($informations['idtuteurentreprise'], "Le tuteur professeur a ajouté un compte rendu pour la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idetudiant'], "Le tuteur professeur a ajouté un compte rendu pour la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idtuteurprof'], "Vous avez ajouté un compte rendu pour la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                        }
                        else {
                            CompteRenduRepository::createCompteRenduAsEntreprise($informations['idtuteurprof'], $informations['idtuteurentreprise'], $informations['idetudiant'], $informations['numconvention'], $data['compteRendu']);
                            NotificationRepository::createNotification($informations['idtuteurprof'], "Le tuteur entreprise a ajouté un compte rendu pour la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idetudiant'], "Le tuteur entreprise a ajouté un compte rendu pour la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idtuteurentreprise'], "Vous avez ajouté un compte rendu pour la convention " . $informations['numconvention'], "/visite/" . $informations['numconvention']);
                        }
                    Application::redirectFromParam("/visite/" . $numConvention);
                }
            }
            return $this->render("visite/compteRendu", [
                'title' => "Compte rendu",
                'form' => $form,
            ]);
        }
        Application::redirectFromParam("/visite");
        return "";
    }

    /**
     * @throws ServerErrorException
     */
    public function compteRenduSoutenance(Request $request): string
    {
        if (Auth::has_role(Roles::Tutor, Roles::TutorTeacher)) {
            $numConvention = $request->getRouteParam("numconvention");
            $informations = ConventionRepository::getInformationByNumConvention($numConvention);
            $form = new FormModel([
                "idTuteurProf" => Application::getUser()->id(),
                "idEtudiant" => $informations['idetudiant'],
                "numConvention" => $numConvention,
                'compteRenduSoutenance' => FormModel::string("Compte rendu de la Soutenance du tuteur professeure")->required(),
            ]);
            if ($request->getMethod() == "post") {
                if ($form->validate($request->getBody())) {
                    $data = $form->getParsedBody();
                    if (CompteRenduRepository::checkIfCompteRenduExist($informations['numconvention'])) {
                        if (Auth::has_role(Roles::TutorTeacher)) {
                            CompteRenduRepository::updateCompteRenduSoutenanceProf($informations['numconvention'], $data['compteRenduSoutenance']);
                            NotificationRepository::createNotification($informations['idtuteurentreprise'], "Le tuteur professeur a modifier le compte rendu de la soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idetudiant'], "Le tuteur professeur a modifier le compte rendu de votre soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idtuteurprof'], "Vous avez modifier le compte rendu de la soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                            if ($informations['idprof'] != null)
                                NotificationRepository::createNotification($informations['idprof'], "Le tuteur professeur a modifier le compte rendu de la  soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                        }

                        else {
                            CompteRenduRepository::updateCompteRenduSoutenanceEntreprise($informations['numconvention'], $data['compteRenduSoutenance']);
                            NotificationRepository::createNotification($informations['idtuteurprof'], "Le tuteur entreprise a ajouté un compte rendu de soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idetudiant'], "Le tuteur entreprise a ajouté un compte rendu de soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                            NotificationRepository::createNotification($informations['idtuteurentreprise'], "Vous avez ajouté un compte rendu de soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                            if ($informations['idprof'] != null)
                                NotificationRepository::createNotification($informations['idprof'], "Le tuteur entreprise a ajouté un compte rendu de soutenance pour la convention " . $informations['numconvention'], "/voirSoutenance/" . $informations['numconvention']);
                        }
                    }
                    Application::redirectFromParam("/voirSoutenance/" . $numConvention);
                }
            }
            return $this->render("visite/compteRendu", [
                'title' => "Compte rendu",
                'form' => $form,
            ]);
        }
        Application::redirectFromParam("/voirSoutenance");
        return "";
    }
}