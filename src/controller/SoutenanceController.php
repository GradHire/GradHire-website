<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\Request;

class SoutenanceController extends AbstractController
{

    public function createSoutenance(Request $request)
    {
        $numConvention = $request->getRouteParam("numConvention");
        if ($numConvention === null) {
            return $this->render("error", [
                "error" => "Numéro de convention non valide"
            ]);
        } else {
            $infosConvention = ConventionRepository::getByNumConvention($numConvention);
            $form = new FormModel([
                'debut_soutenance' => FormModel::Date("Début de la soutenance")->Required()->asterisk()->forget()->withHour(),
                'fin_soutenance' => FormModel::Date("Fin de la soutenance")->Required()->asterisk()->forget()->withHour()
            ]);
            if ($request->getMethod() == "post") {
                if ($form->validate($request->getBody())){
                    $values = array_merge($form->getParsedBody() , [
                        'numconvention' => $infosConvention['numconvention'],
                        'idtuteurprof' => $infosConvention['idtuteurprof'],
                        'idtuteurentreprise' => $infosConvention['idtuteurentreprise'],
                    ]);
                    SoutenanceRepository::createSoutenance($values);
                    Application::redirectFromParam("/conventions");
                }
            }
            return $this->render("soutenance/create", [
                "form" => $form
            ]);
        }
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function etreJury(Request $request): void
    {
        if (Auth::has_role(Roles::Teacher,Roles::TutorTeacher)) {
            $numConvention = $request->getRouteParam("numConvention");
            $idtuteurprof = Application::getUser()->id();
            SoutenanceRepository::seProposerCommeJury($idtuteurprof, $numConvention);
            Application::redirectFromParam("/conventions");
        } else {
            throw new ForbiddenException();
        }
    }

    public function voirSoutenance(Request $request)
    {
        $numConvention = $request->getRouteParam("numConvention");
        $soutenance = SoutenanceRepository::getSoutenanceByNumConvention($numConvention);
        return $this->render("soutenance/voir", [
            "soutenance" => $soutenance
        ]);
    }
}