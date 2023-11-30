<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\CompteRenduRepository;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\VisiteRepository;
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
                    print_r($data);
                    if (CompteRenduRepository::checkIfCompteRenduExist($informations['numconvention'])) {
                        if (Auth::has_role(Roles::TutorTeacher))
                            CompteRenduRepository::updateCompteRenduProf($informations['numconvention'], $data['compteRendu']);
                        else
                            CompteRenduRepository::updateCompteRenduEntreprise($informations['numconvention'], $data['compteRendu']);
                    } else
                        if (Auth::has_role(Roles::TutorTeacher)) CompteRenduRepository::createCompteRenduAsProf($informations['idtuteurprof'], $informations['idtuteurentreprise'], $informations['idetudiant'], $informations['numconvention'], $data['compteRendu']);
                        else CompteRenduRepository::createCompteRenduAsEntreprise($informations['idtuteurprof'], $informations['idtuteurentreprise'], $informations['idetudiant'], $informations['numconvention'], $data['compteRendu']);
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
    public function compteRenduSoutenance(Request $request)
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
                        if (Auth::has_role(Roles::TutorTeacher))
                            CompteRenduRepository::updateCompteRenduSoutenanceProf($informations['numconvention'], $data['compteRenduSoutenance']);
                        else
                            CompteRenduRepository::updateCompteRenduSoutenanceEntreprise($informations['numconvention'], $data['compteRenduSoutenance']);
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
}