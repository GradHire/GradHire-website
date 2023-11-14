<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\dataObject\Staff;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\Request;

class PostulerController extends AbstractController
{

    /**
     * @throws ServerErrorException
     */
    public function se_proposer(Request $request): void
    {
        if (Auth::has_role(Roles::Teacher)) {
            $idOffre = $request->getRouteParams()["id"] ?? null;
            $idEtudiant = (new PostulerRepository())->getByIdOffre($idOffre)[0]->getIdUtilisateur();
            $idUtilisateur = Application::getUser()->id();
            $countPostulation = (new StaffRepository([]))->getCountPostulationTuteur($idUtilisateur);
            if ($countPostulation < 10) {
                (new ConventionRepository([]))->suivreConvention($idUtilisateur,$idOffre,$idEtudiant);
            }
            Application::redirectFromParam("/candidatures");
        }
    }

}