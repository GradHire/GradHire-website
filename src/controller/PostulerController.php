<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Staff;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\Request;

class PostulerController extends AbstractController
{

    public function se_proposer(Request $request): string
    {
        if (Auth::has_role(Roles::Teacher)) {
            $idOffre = $request->getRouteParams()["id"] ?? null;
            $idUtilisateur = Application::getUser()->id();
            $countPostulation = (new StaffRepository([]))->getCountPostulationTuteur($idUtilisateur);
            $countPostulation = 11;
            if ($countPostulation < 10) {
                (new ConventionRepository([]))->suivreConvention($idOffre, $idUtilisateur);
                return $this->render('candidature/listCandidatures', [
                    'success' => "Vous avez postulé avec succès.",
                ]);
            } else {
                return $this->render('candidature/listCandidatures', [
                    'error' => "Vous avez atteint le nombre maximum de postulations possibles pour etre tuteur."
                ]);
            }
        }
    }

}