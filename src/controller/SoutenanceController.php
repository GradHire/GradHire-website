<?php

namespace app\src\controller;

use app\src\model\Application;
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
                'numConvention' => $numConvention,
                'idtuteurprof' => $infosConvention['idtuteurprof'],
                'idtuteurentreprise' => $infosConvention['idtuteurentreprise'],
                'debut_soutenance' => FormModel::Date("Début de la soutenance")->Required()->asterisk()->forget(),
                'fin_soutenance' => FormModel::Date("Fin de la soutenance")->Required()->asterisk()->forget()
            ]);
            if ($request->getMethod() == "post") {
                if ($form->validate($request->getBody())){
                    SoutenanceRepository::createSoutenance($form->getParsedBody());
                    Application::redirectFromParam("/conventions");
                }
            }
            return $this->render("soutenance/create", [
                "form" => $form
            ]);
        }
    }
}