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
use app\src\model\repository\TuteurRepository;
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

    public function se_deproposer(Request $request): void {
        if (Auth::has_role(Roles::Teacher)) {
            $idOffre = $request->getRouteParams()["id"] ?? null;
            $idEtudiant = (new PostulerRepository())->getByIdOffre($idOffre)[0]->getIdUtilisateur();
            $idUtilisateur = Application::getUser()->id();
            $countPostulation = (new StaffRepository([]))->getCountPostulationTuteur($idUtilisateur);
            if($countPostulation >0) {
                (new ConventionRepository([]))->seDeProposer($idUtilisateur,$idOffre,$idEtudiant);
            }
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function listeTuteurPostuler(Request $request): string
    {
        $idOffre = $request->getRouteParams()["idOffre"] ?? null;
        $postulations = (new PostulerRepository())->getTuteurByIdOffre($idOffre);
        $postulationValider = null;
        foreach ($postulations as $postulation) {
            //si le professeur est déjà tuteur de l'étudiant, on le garde
            if ((new TuteurRepository([]))->getIfTuteurAlreadyExist($postulation["idutilisateur"], $idOffre, $postulation["idetudiant"])) {
                $postulationValider[] = $postulation;
                break;
            }
        }
        if ($postulationValider != null) {
            return $this->render('candidature/listeTuteurPostuler', ['postulations' => $postulationValider, 'idOffre' => $idOffre]);
        }
        return $this->render('candidature/listeTuteurPostuler', ['postulations' => $postulations, 'idOffre' => $idOffre]);
    }

    /**
     * @throws ServerErrorException
     */
    public function accepterAsTuteur(Request $request): void{
        if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerAlternance, Roles::ManagerStage)) {
            $idUtilisateur = $request->getRouteParams()["idUser"] ?? null;
            $staff = (new StaffRepository([]))->getByIdFull($idUtilisateur);
            $idOffre = $request->getRouteParams()["idOffre"] ?? null;
            $idEtudiant = (NEW PostulerRepository())->getByIdOffre($idOffre)[0]->getIdUtilisateur();
            $identreprise = (new OffresRepository())->getById($idOffre)->getIdutilisateur();
            (new TuteurRepository([]))->addTuteur($staff->getIdutilisateur(),$idOffre,$idEtudiant,"tuteur",$identreprise);
        }
        Application::redirectFromParam("/postuler/listeTuteur/".$idOffre."/".$idEtudiant);
    }

    /**
     * @throws ServerErrorException
     */
    public function refuserAsTuteur(Request $request): void{
        if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerAlternance, Roles::ManagerStage)) {
            $idUtilisateur = $request->getRouteParams()["idUser"] ?? null;
            $staff = (new StaffRepository([]))->getByIdFull($idUtilisateur);
            $idOffre = $request->getRouteParams()["idOffre"] ?? null;
            $idEtudiant = (NEW PostulerRepository())->getByIdOffre($idOffre)[0]->getIdUtilisateur();
            $identreprise = (new OffresRepository())->getById($idOffre)->getIdutilisateur();
            (new TuteurRepository([]))->removeTuteur($staff->getIdutilisateur(),$idOffre,$idEtudiant,"tuteur",$identreprise);
        }
        Application::redirectFromParam("/postuler/listeTuteur/".$idOffre."/".$idEtudiant);
    }

}